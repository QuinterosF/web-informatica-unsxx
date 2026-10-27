<?php

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Consultas agregadas para el panel de control.
 */
class Dashboard
{
    /**
     * Catálogo de accesos directos por módulo.
     */
    public const MODULE_CARDS = [
        'institucion' => [
            'label' => 'Identidad y Textos',
            'desc' => 'Misión, visión, historia y mensaje del director.',
            'url' => '/institutional-info',
            'color' => 'blue',
        ],
        'portadas' => [
            'label' => 'Fotos Institucionales',
            'desc' => 'Carrusel de imágenes de la portada.',
            'url' => '/portadas',
            'color' => 'amber',
        ],
        'redes' => [
            'label' => 'Directorio y Redes',
            'desc' => 'Teléfonos, WhatsApp y enlaces de servicio.',
            'url' => '/directory',
            'color' => 'teal',
        ],
        'academics' => [
            'label' => 'Plantel Docente',
            'desc' => 'Docentes, coordinadores y personal administrativo.',
            'url' => '/academics',
            'color' => 'blue',
        ],
        'curricular' => [
            'label' => 'Menciones e IIDAI',
            'desc' => 'Áreas curriculares e institutos de investigación.',
            'url' => '/mentions',
            'color' => 'purple',
        ],
        'laboratories' => [
            'label' => 'Laboratorios (Global)',
            'desc' => 'Control de todos los laboratorios y sus galerías.',
            'url' => '/labs',
            'color' => 'teal',
        ],
        'admissions' => [
            'label' => 'Admisión',
            'desc' => 'Pruebas de suficiencia y preuniversitario.',
            'url' => '/admissions',
            'color' => 'green',
        ],
        'communications' => [
            'label' => 'Comunicados',
            'desc' => 'Avisos y comunicados oficiales.',
            'url' => '/announcements',
            'color' => 'amber',
        ],
        'documents' => [
            'label' => 'Documentos Oficiales',
            'desc' => 'Reglamentos, horarios y planes en PDF.',
            'url' => '/documents',
            'color' => 'blue',
        ],
        'extensions' => [
            'label' => 'Extensión y Eventos',
            'desc' => 'Eventos, ICPC, FLISoL y galerías.',
            'url' => '/extensions',
            'color' => 'purple',
        ],
        'logros' => [
            'label' => 'Logros de la Carrera',
            'desc' => 'Premios y reconocimientos históricos.',
            'url' => '/admin/logros',
            'color' => 'amber',
        ],
    ];

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Indicadores globales para el administrador.
     *
     * @return array<int, array{label: string, value: int, sub: string, url: string, color: string}>
     */
    public function getAdminStats(): array
    {
        $academicos = $this->scalar("SELECT COUNT(*) FROM academico");
        $academicosActivos = $this->scalar("SELECT COUNT(*) FROM academico WHERE activo = 1");
        $labs = $this->scalar("SELECT COUNT(*) FROM laboratorio");
        $labsActivos = $this->scalar("SELECT COUNT(*) FROM laboratorio WHERE activo = 1");
        $comunicados = $this->scalar("SELECT COUNT(*) FROM comunicado WHERE estado = 1");
        $documentos = $this->scalar("SELECT COUNT(*) FROM documento_oficial");
        $extensiones = $this->scalar("SELECT COUNT(*) FROM extension WHERE activo = 1");
        $logros = $this->scalar("SELECT COUNT(*) FROM logro WHERE activo = 1");
        $portadas = $this->scalar("SELECT COUNT(*) FROM portada_inicio WHERE activo = 1");
        $usuarios = $this->scalar("SELECT COUNT(*) FROM usuario WHERE estado = 1");
        $pendientes = $this->getPendingLabAccountsCount();

        return [
            [
                'label' => 'Plantel Docente',
                'value' => $academicos,
                'sub' => $academicosActivos . ' activos en el portal',
                'url' => '/academics',
                'color' => 'blue',
            ],
            [
                'label' => 'Laboratorios',
                'value' => $labs,
                'sub' => $labsActivos . ' activos',
                'url' => '/labs',
                'color' => 'teal',
            ],
            [
                'label' => 'Comunicados',
                'value' => $comunicados,
                'sub' => 'publicados',
                'url' => '/announcements',
                'color' => 'amber',
            ],
            [
                'label' => 'Documentos',
                'value' => $documentos,
                'sub' => 'en la biblioteca oficial',
                'url' => '/documents',
                'color' => 'blue',
            ],
            [
                'label' => 'Extensión',
                'value' => $extensiones,
                'sub' => 'eventos activos',
                'url' => '/extensions',
                'color' => 'purple',
            ],
            [
                'label' => 'Logros',
                'value' => $logros,
                'sub' => 'reconocimientos publicados',
                'url' => '/admin/logros',
                'color' => 'amber',
            ],
            [
                'label' => 'Portadas',
                'value' => $portadas,
                'sub' => 'imágenes en el carrusel',
                'url' => '/portadas',
                'color' => 'green',
            ],
            [
                'label' => 'Usuarios activos',
                'value' => $usuarios,
                'sub' => $pendientes > 0
                    ? $pendientes . ' responsable(s) de lab sin cuenta'
                    : 'todas las cuentas al día',
                'url' => '/users',
                'color' => 'blue',
            ],
        ];
    }

    /**
     * Alertas de contenido incompleto o pendiente.
     *
     * @return array<int, array{label: string, count: int, url: string, severity: string}>
     */
    public function getIncompleteAlerts(): array
    {
        $alerts = [];

        $this->addAlert(
            $alerts,
            'Docentes activos sin foto',
            $this->scalar("
                SELECT COUNT(*) FROM academico
                WHERE activo = 1
                  AND (foto_acad IS NULL OR foto_acad = '' OR foto_acad = 'default.png')
            "),
            '/academics',
            'warning'
        );

        $this->addAlert(
            $alerts,
            'Docentes activos sin correo',
            $this->scalar("
                SELECT COUNT(*) FROM academico
                WHERE activo = 1 AND (email_acad IS NULL OR email_acad = '')
            "),
            '/academics',
            'warning'
        );

        $this->addAlert(
            $alerts,
            'Laboratorios activos sin horario PDF',
            $this->scalar("
                SELECT COUNT(*) FROM laboratorio
                WHERE activo = 1 AND (horario_lab IS NULL OR horario_lab = '')
            "),
            '/labs',
            'warning'
        );

        $this->addAlert(
            $alerts,
            'Laboratorios activos sin fotos en galería',
            $this->scalar("
                SELECT COUNT(*) FROM laboratorio l
                WHERE l.activo = 1
                  AND NOT EXISTS (
                      SELECT 1 FROM laboratorio_galeria g WHERE g.id_lab = l.id_lab
                  )
            "),
            '/labs',
            'warning'
        );

        $this->addAlert(
            $alerts,
            'Laboratorios activos sin responsable',
            $this->scalar("
                SELECT COUNT(*) FROM laboratorio
                WHERE activo = 1 AND (resp_acad_id IS NULL OR resp_acad_id = 0)
            "),
            '/labs',
            'danger'
        );

        $this->addAlert(
            $alerts,
            'Comunicados publicados sin imagen',
            $this->scalar("
                SELECT COUNT(*) FROM comunicado
                WHERE estado = 1 AND (foto_com IS NULL OR foto_com = '')
            "),
            '/announcements',
            'warning'
        );

        $this->addAlert(
            $alerts,
            'Documentos sin archivo PDF',
            $this->scalar("
                SELECT COUNT(*) FROM documento_oficial
                WHERE archivo_pdf IS NULL OR archivo_pdf = ''
            "),
            '/documents',
            'danger'
        );

        $this->addAlert(
            $alerts,
            'Menciones activas sin escudo',
            $this->scalar("
                SELECT COUNT(*) FROM mencion_instituto
                WHERE estado = 1 AND (escudo_url IS NULL OR escudo_url = '')
            "),
            '/mentions',
            'warning'
        );

        $sinPortadas = $this->scalar("SELECT COUNT(*) FROM portada_inicio WHERE activo = 1");
        if ($sinPortadas === 0) {
            $alerts[] = [
                'label' => 'El carrusel de portada no tiene imágenes activas',
                'count' => 1,
                'url' => '/portadas',
                'severity' => 'danger',
            ];
        }

        $infoIncompleta = $this->scalar("
            SELECT COUNT(*) FROM info_institucional
            WHERE id = 1
              AND (
                  mision IS NULL OR mision = ''
                  OR vision IS NULL OR vision = ''
                  OR mensaje_director IS NULL OR mensaje_director = ''
              )
        ");
        $this->addAlert(
            $alerts,
            'Información institucional incompleta',
            $infoIncompleta,
            '/institutional-info',
            'warning'
        );

        $this->addAlert(
            $alerts,
            'Responsables de laboratorio sin cuenta',
            $this->getPendingLabAccountsCount(),
            '/users',
            'danger'
        );

        return $alerts;
    }

    /**
     * Últimos registros creados, actualizados o con fotos subidas.
     *
     * @return array<int, array{modulo: string, accion: string, titulo: string, usuario: string, fecha: string, url: string}>
     */
    public function getRecentAudit(int $limit = 8): array
    {
        $limit = max(1, min(50, $limit));

        $sql = "
            SELECT 'Laboratorios' AS modulo, 'Actualizado' AS accion,
                   l.nombre_lab AS titulo,
                   COALESCE(u.usu_nom, 'Sistema') AS usuario,
                   l.actualizado_en AS fecha,
                   CONCAT('/labs/', l.id_lab, '/edit') AS url
            FROM laboratorio l
            LEFT JOIN usuario u ON u.usu_id = l.actualizado_por
            WHERE l.actualizado_por IS NOT NULL
              AND l.actualizado_en IS NOT NULL
              AND l.actualizado_en > '2000-01-01 00:00:00'

            UNION ALL

            SELECT 'Académicos', 'Actualizado',
                   a.nom_acad,
                   COALESCE(u.usu_nom, 'Sistema'),
                   a.actualizado_en,
                   CONCAT('/academics/', a.id_acad, '/edit')
            FROM academico a
            LEFT JOIN usuario u ON u.usu_id = a.actualizado_por
            WHERE a.actualizado_por IS NOT NULL
              AND a.actualizado_en IS NOT NULL
              AND a.actualizado_en > '2000-01-01 00:00:00'

            UNION ALL

            SELECT 'Comunicados',
                   CASE WHEN c.actualizado_por IS NOT NULL AND c.actualizado_por <> c.publicado_por
                        THEN 'Actualizado' ELSE 'Publicado' END,
                   c.nom_com,
                   COALESCE(u.usu_nom, 'Sistema'),
                   c.actualizado_en,
                   CONCAT('/announcements/', c.id_com, '/edit')
            FROM comunicado c
            LEFT JOIN usuario u ON u.usu_id = COALESCE(c.actualizado_por, c.publicado_por)
            WHERE c.actualizado_en IS NOT NULL
              AND c.actualizado_en > '2000-01-01 00:00:00'

            UNION ALL

            SELECT 'Documentos',
                   CASE WHEN d.actualizado_por IS NOT NULL THEN 'Actualizado' ELSE 'Subido' END,
                   d.titulo,
                   COALESCE(u.usu_nom, 'Sistema'),
                   COALESCE(d.actualizado_en, d.created_at),
                   CONCAT('/documents/', d.id_doc, '/edit')
            FROM documento_oficial d
            LEFT JOIN usuario u ON u.usu_id = COALESCE(d.actualizado_por, d.subido_por)
            WHERE COALESCE(d.actualizado_en, d.created_at) IS NOT NULL

            UNION ALL

            SELECT 'Extensión', 'Actualizado',
                   e.nom_ext,
                   COALESCE(u.usu_nom, 'Sistema'),
                   e.actualizado_en,
                   CONCAT('/extensions/', e.id_ext, '/edit')
            FROM extension e
            LEFT JOIN usuario u ON u.usu_id = COALESCE(e.actualizado_por, e.creado_por)
            WHERE e.actualizado_en IS NOT NULL
              AND e.actualizado_en > '2000-01-01 00:00:00'

            UNION ALL

            SELECT 'Logros', 'Actualizado',
                   lg.nom_log,
                   COALESCE(u.usu_nom, 'Sistema'),
                   lg.actualizado_en,
                   CONCAT('/admin/logros/', lg.id_log, '/edit')
            FROM logro lg
            LEFT JOIN usuario u ON u.usu_id = COALESCE(lg.actualizado_por, lg.registrado_por)
            WHERE lg.actualizado_en IS NOT NULL
              AND lg.actualizado_en > '2000-01-01 00:00:00'

            UNION ALL

            SELECT 'Portadas', 'Imagen subida',
                   COALESCE(NULLIF(p.titulo, ''), p.archivo_url),
                   COALESCE(u.usu_nom, 'Sistema'),
                   p.subido_el,
                   '/portadas'
            FROM portada_inicio p
            LEFT JOIN usuario u ON u.usu_id = p.subido_por
            WHERE p.subido_el IS NOT NULL

            UNION ALL

            SELECT 'Menciones', 'Actualizado',
                   m.nombre,
                   COALESCE(u.usu_nom, 'Sistema'),
                   m.actualizado_en,
                   CONCAT('/mentions/', m.id_men, '/edit')
            FROM mencion_instituto m
            LEFT JOIN usuario u ON u.usu_id = COALESCE(m.actualizado_por, m.creado_por)
            WHERE m.actualizado_en IS NOT NULL
              AND m.actualizado_en > '2000-01-01 00:00:00'

            UNION ALL

            SELECT 'Galería de eventos', 'Foto subida',
                   CONCAT('Evento: ', e.nom_ext),
                   COALESCE(u.usu_nom, 'Sistema'),
                   f.subido_en,
                   CONCAT('/extensions/', e.id_ext, '/gallery')
            FROM foto_extension f
            INNER JOIN extension e ON e.id_ext = f.id_ext
            LEFT JOIN usuario u ON u.usu_id = f.subido_por
            WHERE f.subido_en IS NOT NULL

            UNION ALL

            SELECT 'Galería de laboratorios', 'Foto subida',
                   CONCAT('Laboratorio: ', l.nombre_lab),
                   COALESCE(u.usu_nom, 'Sistema'),
                   g.subido_el,
                   CONCAT('/labs/', l.id_lab, '/gallery')
            FROM laboratorio_galeria g
            INNER JOIN laboratorio l ON l.id_lab = g.id_lab
            LEFT JOIN usuario u ON u.usu_id = g.subido_por
            WHERE g.subido_el IS NOT NULL

            UNION ALL

            SELECT 'Información institucional', 'Actualizado',
                   'Textos y mensaje del director',
                   COALESCE(u.usu_nom, 'Sistema'),
                   i.updated_at,
                   '/institutional-info'
            FROM info_institucional i
            LEFT JOIN usuario u ON u.usu_id = i.actualizado_por
            WHERE i.updated_at IS NOT NULL
              AND i.updated_at > '2000-01-01 00:00:00'

            UNION ALL

            SELECT ae.modulo, 'Eliminado',
                   ae.titulo_registro,
                   COALESCE(u.usu_nom, 'Sistema'),
                   ae.eliminado_en,
                   CASE ae.modulo
                       WHEN 'Documentos' THEN '/documents'
                       WHEN 'Comunicados' THEN '/announcements'
                       WHEN 'Extensión' THEN '/extensions'
                       WHEN 'Logros' THEN '/admin/logros'
                       WHEN 'Académicos' THEN '/academics'
                       WHEN 'Laboratorios' THEN '/labs'
                       WHEN 'Portadas' THEN '/portadas'
                       WHEN 'Menciones' THEN '/mentions'
                       ELSE '/dashboard'
                   END
            FROM auditoria_eliminacion ae
            LEFT JOIN usuario u ON u.usu_id = ae.eliminado_por
            WHERE ae.eliminado_en IS NOT NULL

            ORDER BY fecha DESC
            LIMIT {$limit}
        ";

        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['fecha'] = $this->formatDate($row['fecha'] ?? null);
        }
        unset($row);

        return $rows;
    }

    /**
     * Cuenta los responsables de laboratorio activos que aún no tienen usuario.
     */
    public function getPendingLabAccountsCount(): int
    {
        return $this->scalar("
            SELECT COUNT(DISTINCT a.id_acad)
            FROM laboratorio l
            INNER JOIN academico a ON a.id_acad = l.resp_acad_id
            WHERE l.activo = 1
              AND l.resp_acad_id IS NOT NULL
              AND a.usu_id IS NULL
              AND a.email_acad IS NOT NULL
              AND a.email_acad LIKE '%_@_%._%'
        ");
    }

    /**
     * Tarjetas de módulos permitidos para un operador.
     *
     * @param string[] $modules
     * @return array<int, array{key: string, label: string, desc: string, url: string, color: string}>
     */
    public function getModuleCards(array $modules, bool $hasAssignedLabs): array
    {
        $cards = [];

        foreach ($modules as $module) {
            if (!isset(self::MODULE_CARDS[$module])) {
                continue;
            }

            $cards[] = ['key' => $module] + self::MODULE_CARDS[$module];
        }

        if ($hasAssignedLabs && !in_array('laboratories', $modules, true)) {
            $cards[] = [
                'key' => 'laboratories',
                'label' => 'Mis Laboratorios',
                'desc' => 'Horarios y galerías de los laboratorios a tu cargo.',
                'url' => '/labs',
                'color' => 'teal',
            ];
        }

        return $cards;
    }

    /**
     * Agrega una alerta solo cuando el contador es mayor que cero.
     */
    private function addAlert(array &$alerts, string $label, int $count, string $url, string $severity): void
    {
        if ($count > 0) {
            $alerts[] = [
                'label' => $label,
                'count' => $count,
                'url' => $url,
                'severity' => $severity,
            ];
        }
    }

    /**
     * Ejecuta una consulta escalar con parámetros opcionales.
     */
    private function scalar(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Formatea una fecha para mostrarla en el panel.
     */
    private function formatDate(?string $date): string
    {
        if ($date === null || $date === '') {
            return '—';
        }

        $timestamp = strtotime($date);
        return $timestamp !== false ? date('d/m/Y H:i', $timestamp) : '—';
    }
}
