<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Dashboard;
use App\Models\Lab;
use App\Models\PublicPortal;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/home', $portal, [
            'title' => 'Ingeniería Informática - UNSXX',
            'pageTitle' => 'Inicio',
            'portadas' => $portal->getPortadas(),
            'academics' => $portal->getAcademicsGrouped(),
            'mentions' => $portal->getMentions(),
            'admissions' => $portal->getAdmissions(),
            'announcements' => $portal->getAnnouncements(3),
            'extensions' => $portal->getExtensionsWithGallery(3),
            'extensionCategories' => $portal->getExtensionCategories(),
            'logros' => $portal->getLogros(4),
            'labs' => $portal->getLabsWithGallery(),
        ]);
    }

    public function nosotros(): void
    {
        $portal = new PublicPortal();
        $academics = $portal->getAcademicsGrouped();

        $this->renderPublicView('public/carrera/nosotros', $portal, [
            'title' => 'Nosotros - Ingeniería Informática UNSXX',
            'pageTitle' => 'Nosotros',
            'authorities' => $academics['authorities'],
            'academics' => $academics,
        ]);
    }

    public function admision(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/carrera/admision', $portal, [
            'title' => 'Admisión - Ingeniería Informática UNSXX',
            'pageTitle' => 'Admisión',
            'admissions' => $portal->getAdmissions(),
        ]);
    }

    public function titulacion(): void
    {
        $portal = new PublicPortal();
        $todosLosDocumentos = $portal->getOfficialDocuments();

        // Filtramos únicamente los PDF con categoría 'reglamento_titulacion'
        $reglamentosTitulacion = array_values(array_filter(
            $todosLosDocumentos,
            static fn(array $doc): bool => ($doc['categoria'] ?? '') === 'reglamento_titulacion'
        ));

        $this->renderPublicView('public/carrera/titulacion', $portal, [
            'title' => 'Titulación - Ingeniería Informática UNSXX',
            'pageTitle' => 'Modalidades de Titulación',
            'documents' => $reglamentosTitulacion,
        ]);
    }

    public function becas(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/carrera/becas', $portal, [
            'title' => 'Becas - Ingeniería Informática UNSXX',
            'pageTitle' => 'Becas',
        ]);
    }

    public function contacto(): void
    {
        $portal = new PublicPortal();
        $layout = $portal->getCommonLayoutData();

        $celulares = $layout['phones'] ?? [];

        $enlacesServicios = $layout['enlacesServicios'] ?? [];
        $enlacesInvestigacion = $layout['enlacesInvestigacion'] ?? [];
        $enlacesFooterInstitucional = $layout['enlacesFooterInstitucional'] ?? [];
        $enlacesRedesSociales = $layout['enlacesRedesSociales'] ?? [];
        $telefonoSoporte = $layout['telefonoSoporte'] ?? null;

        $pageTitle = 'Contacto y Ubicación';

        ob_start();
        $this->view('public/contacto/index', [
            'celulares' => $celulares,
            'enlacesRedesSociales' => $enlacesRedesSociales,
            'pageTitle' => $pageTitle,
        ]);
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layouts/public.php';
    }

    public function menciones(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/academico/menciones', $portal, [
            'title' => 'Menciones - Ingeniería Informática UNSXX',
            'pageTitle' => 'Menciones',
            'mentions' => $portal->getMentions(),
        ]);
    }

    public function plantel(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/academico/plantel', $portal, [
            'title' => 'Plantel Docente - Ingeniería Informática UNSXX',
            'pageTitle' => 'Plantel Docente',
            'academics' => $portal->getAcademicsGrouped(),
        ]);
    }

    public function laboratorios(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/laboratorios/index', $portal, [
            'title' => 'Laboratorios - Ingeniería Informática UNSXX',
            'pageTitle' => 'Laboratorios',
            'labs' => $portal->getLabsWithGallery(),
        ]);
    }

    public function comunicados(): void
    {
        $portal = new PublicPortal();

        $porPagina = 10;
        $paginaActual = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $totalComunicados = $portal->countAnnouncements();
        $totalPaginas = max(1, (int) ceil($totalComunicados / $porPagina));

        if ($paginaActual > $totalPaginas) {
            $paginaActual = $totalPaginas;
        }

        $offset = ($paginaActual - 1) * $porPagina;
        $comunicados = $portal->getAnnouncementsPaginated($porPagina, $offset);

        $this->renderPublicView('public/comunidad/comunicados', $portal, [
            'title' => 'Comunicados - Ingeniería Informática UNSXX',
            'pageTitle' => 'Comunicados y Avisos',
            'announcements' => $comunicados,
            'totalComunicados' => $totalComunicados,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina,
        ]);
    }

    public function eventos(?string $slug = null): void
    {
        $portal = new PublicPortal();
        $categorias = $portal->getExtensionCategories();

        // Soportar tanto ruta SEO (/extension/ciberseguridad-ctf) como query (?cat=id)
        $slugParam = $slug !== null ? trim($slug) : trim((string) ($_GET['categoria'] ?? ''));
        $categoriaActiva = 0;
        $slugActivo = '';

        if ($slugParam !== '') {
            foreach ($categorias as $cat) {
                if (strcasecmp((string) ($cat['slug_cat'] ?? ''), $slugParam) === 0) {
                    $categoriaActiva = (int) $cat['id_cat_ext'];
                    $slugActivo = (string) $cat['slug_cat'];
                    break;
                }
            }
        } elseif (isset($_GET['cat'])) {
            $idBuscado = max(0, (int) $_GET['cat']);
            foreach ($categorias as $cat) {
                if ((int) ($cat['id_cat_ext'] ?? 0) === $idBuscado) {
                    $categoriaActiva = $idBuscado;
                    $slugActivo = (string) ($cat['slug_cat'] ?? '');
                    break;
                }
            }
        }

        $catIdFiltro = $categoriaActiva > 0 ? $categoriaActiva : null;

        $porPagina = 10;
        $paginaActual = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $totalActividades = $portal->countExtensions($catIdFiltro);
        $totalPaginas = max(1, (int) ceil($totalActividades / $porPagina));

        if ($paginaActual > $totalPaginas) {
            $paginaActual = $totalPaginas;
        }

        $offset = ($paginaActual - 1) * $porPagina;
        $extensions = $portal->getExtensionsPaginated($porPagina, $offset, $catIdFiltro);

        $this->renderPublicView('public/comunidad/eventos', $portal, [
            'title' => 'Extensión Universitaria - Ingeniería Informática UNSXX',
            'pageTitle' => 'Extensión Universitaria',
            'extensions' => $extensions,
            'extensionCategories' => $categorias,
            'categoriaActiva' => $categoriaActiva,
            'slugActivo' => $slugActivo,
            'totalActividades' => $totalActividades,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina,
        ]);
    }

    public function logros(): void
    {
        $portal = new PublicPortal();

        $this->renderPublicView('public/comunidad/logros', $portal, [
            'title' => 'Logros - Ingeniería Informática UNSXX',
            'pageTitle' => 'Logros',
            'logros' => $portal->getLogros(),
        ]);
    }

    public function carrera(): void
    {
        $this->nosotros();
    }

    public function comunidad(): void
    {
        $this->comunicados();
    }

    /**
     * Comparte los datos del layout público con la vista solicitada
     * y la envuelve automáticamente en src/Views/layouts/public.php
     */
    private function renderPublicView(string $view, PublicPortal $portal, array $data = []): void
    {
        $layout = $portal->getCommonLayoutData();
        $telefonoSoporte = $layout['telefonoSoporte'] ?? null;

        $viewData = array_merge([
            'layout' => $layout,
            'common' => $layout,
            'phones' => $layout['phones'] ?? [],
            'supportPhone' => $telefonoSoporte,
            'telefonoSoporte' => $telefonoSoporte,
            'links' => $layout['links'] ?? [],
            'info' => $layout['info'] ?? [],
            'navLabs' => $portal->getNavLabs(),
            'extensionCategories' => $portal->getExtensionCategories(),
            'enlacesServicios' => $layout['enlacesServicios'] ?? [],
            'enlacesInvestigacion' => $layout['enlacesInvestigacion'] ?? [],
            'enlacesFooterInstitucional' => $layout['enlacesFooterInstitucional'] ?? [],
            'enlacesRedesSociales' => $layout['enlacesRedesSociales'] ?? [],
        ], $data);

        ob_start();
        $this->view($view, $viewData);
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layouts/public.php';
    }

    /**
     * Panel único: el administrador ve indicadores y auditoría; los
     * operadores ven sus módulos y laboratorios asignados.
     */
    public function dashboard(): void
    {
        $user = Session::getUser();
        $isAdmin = Auth::isAdmin();
        $dashboard = new Dashboard();

        $data = [
            'user' => $user,
            'isAdmin' => $isAdmin,
        ];

        if ($isAdmin) {
            $userModel = new User();

            $data['stats'] = $dashboard->getAdminStats();
            $data['alerts'] = $dashboard->getIncompleteAlerts();
            $data['audit'] = $dashboard->getRecentAudit(8);
            $data['pendingLabAccounts'] = $dashboard->getPendingLabAccountsCount();
            $data['pendingResets'] = $userModel->getPendingResetUsers();
        } else {
            $modules = $_SESSION['user_modules'] ?? [];
            if (!is_array($modules)) {
                $modules = [];
            }

            $labIds = $_SESSION['user_lab_ids'] ?? [];
            if (!is_array($labIds)) {
                $labIds = [];
            }

            $labModel = new Lab();

            $data['moduleCards'] = $dashboard->getModuleCards($modules, !empty($labIds));
            $data['labs'] = !empty($labIds) ? $labModel->findByIds($labIds) : [];
        }

        $this->view('home/dashboard', $data);
    }

    /**
     * Compatibilidad: el panel de administración ahora es /dashboard.
     */
    public function adminDashboard(): void
    {
        $this->redirect('/dashboard');
    }

    public function autoridades(): void
    {
        $portal = new PublicPortal();
        $academics = $portal->getAcademicsGrouped();

        $this->renderPublicView('public/carrera/autoridades', $portal, [
            'title' => 'Autoridades - Ingeniería Informática UNSXX',
            'pageTitle' => 'Autoridades',
            'authorities' => $academics['authorities'],
            'academics' => $academics,
        ]);
    }

    public function documentos(): void
    {
        $portal = new PublicPortal();
        $todosLosDocumentos = $portal->getOfficialDocuments();

        // Filtramos solo reglamentos y documentos institucionales
        // ('plan_estudios' va en /academico/plan-estudios y 'horario_clases' va en /horarios)
        $documentosInstitucionales = array_values(array_filter(
            $todosLosDocumentos,
            static function (array $doc): bool {
                $cat = (string) ($doc['categoria'] ?? 'otro');
                return in_array($cat, ['reglamento_interno', 'reglamento_titulacion', 'otro'], true);
            }
        ));

        $this->renderPublicView('public/academico/documentos', $portal, [
            'title' => 'Reglamentos y Documentos - Ingeniería Informática UNSXX',
            'pageTitle' => 'Reglamentos y Documentos',
            'documents' => $documentosInstitucionales,
        ]);
    }

    public function planEstudios(): void
    {
        $portal = new PublicPortal();
        $todosLosDocumentos = $portal->getOfficialDocuments();

        // Filtramos únicamente los PDF con categoría 'plan_estudios' (Malla Curricular y Plan de Estudios)
        $documentosPlan = array_values(array_filter(
            $todosLosDocumentos,
            static fn(array $doc): bool => ($doc['categoria'] ?? '') === 'plan_estudios'
        ));

        $this->renderPublicView('public/academico/plan_estudios', $portal, [
            'title' => 'Plan de Estudios - Ingeniería Informática UNSXX',
            'pageTitle' => 'Plan de Estudios',
            'documents' => $documentosPlan,
            'mentions' => $portal->getMentions(),
        ]);
    }

    public function horarios(): void
    {
        $portal = new PublicPortal();
        $todosLosDocumentos = $portal->getOfficialDocuments();

        // Filtramos únicamente los PDF con categoría 'horario_clases' (1ro a 5to año)
        $horarios = array_values(array_filter(
            $todosLosDocumentos,
            static fn(array $doc): bool => ($doc['categoria'] ?? '') === 'horario_clases'
        ));

        // Ordenamos de 1er a 5to año por el título del documento
        usort($horarios, static function (array $a, array $b): int {
            return strnatcasecmp((string) ($a['titulo'] ?? ''), (string) ($b['titulo'] ?? ''));
        });

        $this->renderPublicView('public/academico/horarios', $portal, [
            'title' => 'Horarios de Clases - Ingeniería Informática UNSXX',
            'pageTitle' => 'Horarios de Clases',
            'schedules' => $horarios,
        ]);
    }

    public function mencionSeguridad(): void
    {
        $this->renderMencionOInstituto('seguridad', false);
    }

    public function mencionMovil(): void
    {
        $this->renderMencionOInstituto('movil', false);
    }

    public function institutoIidai(): void
    {
        $this->renderMencionOInstituto('iidai', true);
    }

    /**
     * Busca la Mención o Instituto IIDAI en la tabla `mencion_instituto`
     * y renderiza la vista única reutilizable.
     */
    private function renderMencionOInstituto(string $keyword, bool $esIidai): void
    {
        $portal = new PublicPortal();
        $mentions = $portal->getMentions();
        $itemSeleccionado = null;

        foreach ($mentions as $m) {
            $nomLower = mb_strtolower((string) ($m['nombre'] ?? ''), 'UTF-8');
            if ($keyword === 'seguridad' && str_contains($nomLower, 'seguridad')) {
                $itemSeleccionado = $m;
                break;
            }
            if ($keyword === 'movil' && (str_contains($nomLower, 'móvil') || str_contains($nomLower, 'movil'))) {
                $itemSeleccionado = $m;
                break;
            }
            if ($keyword === 'iidai' && str_contains($nomLower, 'iidai')) {
                $itemSeleccionado = $m;
                break;
            }
        }

        $nombreMostrar = $esIidai
            ? 'Instituto de Investigación y Desarrollo de Aplicaciones Informáticas (IIDAI)'
            : 'Mención ' . ($itemSeleccionado['nombre'] ?? '');

        $this->renderPublicView('public/academico/mencion_detalle', $portal, [
            'title'     => $nombreMostrar . ' - Ingeniería Informática UNSXX',
            'pageTitle' => $esIidai ? 'Instituto IIDAI' : 'Mención ' . ($itemSeleccionado['nombre'] ?? ''),
            'item'      => $itemSeleccionado,
            'esIidai'   => $esIidai,
        ]);
    }
}