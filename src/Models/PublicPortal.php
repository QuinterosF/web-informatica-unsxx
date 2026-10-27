<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class PublicPortal
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Datos compartidos por el layout público.
     */
    public function getCommonLayoutData(): array
    {
        $phones = $this->getActivePhones();
        $supportPhone = $this->getSupportPhone();
        $layoutLinks = $this->getLayoutLinks();

        $info = $this->pdo->query("
            SELECT *
            FROM info_institucional
            ORDER BY id ASC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);

        return array_merge([
            'phones' => $phones,
            'supportPhone' => $supportPhone,
            'telefonoSoporte' => $supportPhone,
            'info' => $info ?: [],
        ], $layoutLinks);
    }

    /**
     * Celulares activos; el soporte técnico queda al final.
     */
    public function getActivePhones(): array
    {
        return $this->pdo->query("
            SELECT id_cel, nom_cel, num_cel, icono, es_soporte
            FROM celular
            WHERE estado = 1
            ORDER BY es_soporte ASC, id_cel ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Celular activo marcado como soporte técnico.
     */
    public function getSupportPhone(): ?array
    {
        $phone = $this->pdo->query("
            SELECT id_cel, nom_cel, num_cel, icono, es_soporte
            FROM celular
            WHERE estado = 1 AND es_soporte = 1
            ORDER BY id_cel ASC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);

        return $phone ?: null;
    }

    /**
     * Enlaces activos agrupados por ubicación para navbar/footer.
     *
     * @return array{
     *     enlacesServicios: array,
     *     enlacesInvestigacion: array,
     *     enlacesFooterInstitucional: array,
     *     enlacesRedesSociales: array,
     *     links: array
     * }
     */
    public function getLayoutLinks(): array
    {
        $rows = $this->pdo->query("
            SELECT e.id_enl, e.nombre, e.url, e.icono, e.estado, u.ubicacion
            FROM enlace_servicio e
            INNER JOIN ubicacion_enlace u ON u.id_enl = e.id_enl
            WHERE e.estado = 1
            ORDER BY e.id_enl ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $layout = [
            'enlacesServicios' => [],
            'enlacesInvestigacion' => [],
            'enlacesFooterInstitucional' => [],
            'enlacesRedesSociales' => [],
        ];

        $locationMap = [
            'menu_servicios' => 'enlacesServicios',
            'menu_investigacion' => 'enlacesInvestigacion',
            'footer_enlaces' => 'enlacesFooterInstitucional',
            'footer_redes' => 'enlacesRedesSociales',
        ];

        $links = [];

        foreach ($rows as $row) {
            $location = (string) ($row['ubicacion'] ?? '');

            if (isset($locationMap[$location])) {
                $layout[$locationMap[$location]][] = $row;
            }

            if (!isset($links[(int) $row['id_enl']])) {
                $links[(int) $row['id_enl']] = $row;
            }
        }

        $layout['links'] = array_values($links);

        return $layout;
    }

    /**
     * Portadas activas del inicio.
     */
    public function getPortadas(): array
    {
        return $this->pdo->query("
            SELECT *
            FROM portada_inicio
            WHERE activo = 1
            ORDER BY orden ASC, id_portada DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Plantel académico agrupado y con datos de contacto protegidos.
     */
    public function getAcademicsGrouped(): array
    {
        $categories = $this->pdo->query("
            SELECT cat_id, cat_nombre
            FROM categoria_academico
            ORDER BY cat_id ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $rows = $this->pdo->query("
            SELECT
                a.id_acad,
                a.nom_acad,
                a.foto_acad,
                a.cel_acad,
                a.mostrar_cel,
                a.email_acad,
                a.mostrar_email,
                a.sitio_web,
                a.cat_acad,
                a.desc_acad,
                a.activo,
                c.cat_nombre
            FROM academico a
            INNER JOIN categoria_academico c ON c.cat_id = a.cat_acad
            WHERE a.activo = 1
            ORDER BY c.cat_id ASC, a.nom_acad ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $all = array_map([$this, 'protectAcademicContactData'], $rows);

        $authorities = array_values(array_filter($all, [$this, 'isAuthorityCategory']));
        $teachers = array_values(array_filter($all, [$this, 'isTeacherCategory']));

        // Jerarquía oficial de autoridades según cat_id.
        $authorityOrder = [7, 14, 16, 18, 15];

        usort($authorities, static function (array $a, array $b) use ($authorityOrder): int {
            $aCat = (int) ($a['cat_acad'] ?? 0);
            $bCat = (int) ($b['cat_acad'] ?? 0);

            $aRank = array_search($aCat, $authorityOrder, true);
            $bRank = array_search($bCat, $authorityOrder, true);

            $aRank = $aRank === false ? count($authorityOrder) : $aRank;
            $bRank = $bRank === false ? count($authorityOrder) : $bRank;

            if ($aRank !== $bRank) {
                return $aRank <=> $bRank;
            }

            if ($aCat !== $bCat) {
                return $aCat <=> $bCat;
            }

            return strcasecmp((string) ($a['nom_acad'] ?? ''), (string) ($b['nom_acad'] ?? ''));
        });

        return [
            'categories' => $categories,
            'authorities' => $authorities,
            'teachers' => $teachers,
            'all' => $all,
        ];
    }

    /**
     * Menciones activas con datos de su coordinador.
     */
    /**
     * Menciones e Instituto IIDAI activos con perfil completo de su coordinador.
     */
    public function getMentions(): array
    {
        $rows = $this->pdo->query("
            SELECT m.*,
                   a.nom_acad AS coordinador_nombre,
                   a.foto_acad AS coordinador_foto,
                   a.email_acad AS coordinador_email,
                   a.mostrar_email,
                   a.cel_acad AS coordinador_cel,
                   a.mostrar_cel,
                   a.sitio_web AS coordinador_web,
                   a.desc_acad AS coordinador_desc,
                   c.cat_nombre AS coordinador_categoria
            FROM mencion_instituto m
            LEFT JOIN academico a ON a.id_acad = m.coordinador
            LEFT JOIN categoria_academico c ON c.cat_id = a.cat_acad
            WHERE m.estado = 1
            ORDER BY m.id_men ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            if ((int) ($row['mostrar_email'] ?? 0) === 0) {
                $row['coordinador_email'] = null;
            }
            if ((int) ($row['mostrar_cel'] ?? 0) === 0) {
                $row['coordinador_cel'] = null;
            }
        }
        unset($row);

        return $rows;
    }

    /**
     * Procesos de admisión activos.
     */
    public function getAdmissions(): array
    {
        return $this->pdo->query("
            SELECT *
            FROM admision
            WHERE estado = 1
            ORDER BY gestion DESC, id_adm DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Laboratorios activos con su galería y foto de portada.
     */
    /**
     * Laboratorios activos con su galería, foto de portada y perfil completo del responsable.
     */
    public function getLabsWithGallery(): array
    {
        $labs = $this->pdo->query("
            SELECT l.*,
                a.nom_acad AS responsable_nombre,
                a.foto_acad AS responsable_foto,
                a.email_acad AS responsable_email,
                a.mostrar_email,
                a.cel_acad AS responsable_cel,
                a.mostrar_cel,
                a.sitio_web AS responsable_web,
                a.desc_acad AS responsable_desc,
                c.cat_nombre AS responsable_categoria
            FROM laboratorio l
            LEFT JOIN academico a ON a.id_acad = l.resp_acad_id
            LEFT JOIN categoria_academico c ON c.cat_id = a.cat_acad
            WHERE l.activo = 1
            ORDER BY l.id_lab ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $photosStmt = $this->pdo->prepare("
            SELECT *
            FROM laboratorio_galeria
            WHERE id_lab = ?
            ORDER BY es_portada DESC, id_foto ASC
        ");

        foreach ($labs as &$lab) {
            if ((int) ($lab['mostrar_email'] ?? 0) === 0) {
                $lab['responsable_email'] = null;
            }
              if ((int) ($lab['mostrar_cel'] ?? 0) === 0) {
                  $lab['responsable_cel'] = null;
              }

              // Evita enlaces 404 cuando el PDF de horario no existe físicamente.
              $scheduleFile = trim((string) ($lab['horario_lab'] ?? ''));
              $schedulePath = __DIR__ . '/../../public/uploads/pdf/laboratorios/' . $scheduleFile;

              if ($scheduleFile === '' || !is_file($schedulePath)) {
                  $lab['horario_lab'] = null;
              }

              $photosStmt->execute([(int) $lab['id_lab']]);
            $photos = $photosStmt->fetchAll(PDO::FETCH_ASSOC);

            $lab['fotos'] = $photos;
            $lab['portada'] = $photos[0] ?? null;
        }
        unset($lab);

        return $labs;
    }

    /**
     * Laboratorios activos mínimos para la navegación pública.
     */
    public function getNavLabs(): array
    {
        return $this->pdo->query("
            SELECT id_lab, nombre_lab
            FROM laboratorio
            WHERE activo = 1
            ORDER BY id_lab ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Comunicados publicados, con límite opcional.
     */
    public function getAnnouncements(?int $limit = null): array
    {
        $sql = "
            SELECT *
            FROM comunicado
            WHERE estado = 1
            ORDER BY fijado DESC, fech_com DESC, id_com DESC
        ";

        return $this->fetchAllWithOptionalLimit($sql, $limit);
    }

    /**
     * Cuenta el total de comunicados publicados para la paginación.
     */
    public function countAnnouncements(): int
    {
        return (int) $this->pdo->query("
            SELECT COUNT(*)
            FROM comunicado
            WHERE estado = 1
        ")->fetchColumn();
    }

    /**
     * Obtiene comunicados publicados con paginación (LIMIT y OFFSET).
     */
    public function getAnnouncementsPaginated(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM comunicado
            WHERE estado = 1
            ORDER BY fijado DESC, fech_com DESC, id_com DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Último comunicado activo marcado como fijado.
     */
    public function getPinnedAnnouncement(): ?array
    {
        $announcement = $this->pdo->query("
            SELECT *
            FROM comunicado
            WHERE estado = 1 AND fijado = 1
            ORDER BY fech_com DESC, id_com DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);

        return $announcement ?: null;
    }

    /**
     * Eventos de extensión activos con su galería y portada.
     */
    public function getExtensionsWithGallery(?int $limit = null): array
    {
        $sql = "
            SELECT e.*, c.nom_cat, c.slug_cat, c.color_badge
            FROM extension e
            LEFT JOIN categoria_extension c ON c.id_cat_ext = e.id_cat_ext
            WHERE e.activo = 1
            ORDER BY e.destacado DESC, e.fech_ext DESC, e.id_ext DESC
        ";

        if ($limit !== null && $limit > 0) {
            $stmt = $this->pdo->prepare($sql . ' LIMIT :limit');
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $extensions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $extensions = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        $photosStmt = $this->pdo->prepare("
            SELECT *
            FROM foto_extension
            WHERE id_ext = ?
            ORDER BY es_portada DESC, id_foto ASC
        ");

        foreach ($extensions as &$extension) {
            $photosStmt->execute([(int) $extension['id_ext']]);
            $photos = $photosStmt->fetchAll(PDO::FETCH_ASSOC);

            $extension['fotos'] = $photos;
            $extension['portada'] = $photos[0] ?? null;
        }
        unset($extension);

        return $extensions;
    }

    /**
     * Cuenta el total de actividades de extensión activas (con filtro opcional por categoría).
     */
    public function countExtensions(?int $categoryId = null): int
    {
        if ($categoryId !== null && $categoryId > 0) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM extension
                WHERE activo = 1 AND id_cat_ext = :cat
            ");
            $stmt->bindValue(':cat', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        }

        return (int) $this->pdo->query("
            SELECT COUNT(*)
            FROM extension
            WHERE activo = 1
        ")->fetchColumn();
    }

    /**
     * Obtiene actividades de extensión paginadas (10 por página) con su galería de fotos.
     */
    public function getExtensionsPaginated(int $limit = 10, int $offset = 0, ?int $categoryId = null): array
    {
        $whereCat = ($categoryId !== null && $categoryId > 0) ? 'AND e.id_cat_ext = :cat' : '';

        $sql = "
            SELECT e.*, c.nom_cat, c.slug_cat, c.color_badge
            FROM extension e
            LEFT JOIN categoria_extension c ON c.id_cat_ext = e.id_cat_ext
            WHERE e.activo = 1 {$whereCat}
            ORDER BY e.destacado DESC, e.fech_ext DESC, e.id_ext DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        if ($categoryId !== null && $categoryId > 0) {
            $stmt->bindValue(':cat', $categoryId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $stmt->execute();

        $extensions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $photosStmt = $this->pdo->prepare("
            SELECT *
            FROM foto_extension
            WHERE id_ext = ?
            ORDER BY es_portada DESC, id_foto ASC
        ");

        foreach ($extensions as &$extension) {
            $photosStmt->execute([(int) $extension['id_ext']]);
            $photos = $photosStmt->fetchAll(PDO::FETCH_ASSOC);

            $extension['fotos'] = $photos;
            $extension['portada'] = $photos[0] ?? null;
        }
        unset($extension);

        return $extensions;
    }

    /**
     * Categorías activas de extensión.
     */
    public function getExtensionCategories(): array
    {
        return $this->pdo->query("
            SELECT *
            FROM categoria_extension
            WHERE activo = 1
            ORDER BY nom_cat ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Logros activos, con límite opcional.
     */
    public function getLogros(?int $limit = null): array
    {
        $sql = "
            SELECT *
            FROM logro
            WHERE activo = 1
            ORDER BY fech_log DESC, id_log DESC
        ";

        return $this->fetchAllWithOptionalLimit($sql, $limit);
    }

    /**
     * Documentos oficiales publicados.
     */
    public function getOfficialDocuments(): array
    {
        return $this->pdo->query("
            SELECT *
            FROM documento_oficial
            WHERE estado = 1
            ORDER BY created_at DESC, id_doc DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ejecuta una consulta con LIMIT opcional usando parámetros seguros.
     */
    private function fetchAllWithOptionalLimit(string $sql, ?int $limit): array
    {
        if ($limit !== null && $limit > 0) {
            $stmt = $this->pdo->prepare($sql . ' LIMIT :limit');
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Oculta celular y correo cuando el académico no autorizó su publicación.
     */
    private function protectAcademicContactData(array $academic): array
    {
        if ((int) ($academic['mostrar_cel'] ?? 0) === 0) {
            $academic['cel_acad'] = null;
        }

        if ((int) ($academic['mostrar_email'] ?? 0) === 0) {
            $academic['email_acad'] = null;
        }

        return $academic;
    }

    /**
     * Identifica las categorías que forman parte de la autoridad institucional.
     */
    private function isAuthorityCategory(array $academic): bool
    {
        $category = mb_strtolower((string) ($academic['cat_nombre'] ?? ''), 'UTF-8');

        $isAuthority = str_contains($category, 'director')
            || str_contains($category, 'coordinador')
            || str_contains($category, 'coord.');

        $isExcluded = str_contains($category, 'secretar')
            || str_contains($category, 'administrativo')
            || str_contains($category, 'responsable lab');

        return $isAuthority && !$isExcluded;
    }

    /**
     * Mantiene a todo el plantel activo excepto secretaría y administrativos.
     */
    private function isTeacherCategory(array $academic): bool
    {
        $category = mb_strtolower((string) ($academic['cat_nombre'] ?? ''), 'UTF-8');

        return !str_contains($category, 'secretar')
            && !str_contains($category, 'administrativo');
    }
}
