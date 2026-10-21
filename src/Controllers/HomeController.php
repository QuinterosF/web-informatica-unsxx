<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Dashboard;
use App\Models\Lab;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', []);
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
            $data['stats'] = $dashboard->getAdminStats();
            $data['alerts'] = $dashboard->getIncompleteAlerts();
            $data['audit'] = $dashboard->getRecentAudit(8);
            $data['pendingLabAccounts'] = $dashboard->getPendingLabAccountsCount();
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
}