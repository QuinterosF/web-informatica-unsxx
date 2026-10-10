<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', []);
    }

    public function dashboard(): void
    {
        $user = Session::getUser();
        $this->view('home/dashboard', ['user' => $user]);
    }

    public function adminDashboard(): void
    {
        $user = Session::getUser();
        $this->view('home/admin', ['user' => $user]);
    }
}