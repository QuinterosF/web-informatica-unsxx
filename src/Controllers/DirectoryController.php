<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Phone;
use App\Models\ServiceLink;

class DirectoryController extends Controller
{
    private Phone $phoneModel;
    private ServiceLink $linkModel;

    public function __construct()
    {
        $this->phoneModel = new Phone();
        $this->linkModel = new ServiceLink();
    }

    /**
     * Listado de Directorio y Redes
     * GET /directory
     */
    public function index(): void
    {
        $phones = $this->phoneModel->all();
        $links = $this->linkModel->all();

        $this->view('directory/index', [
            'phones' => $phones,
            'links' => $links,
            'title' => 'Directorio y Redes - Ingeniería Informática UNSXX',
            'pageTitle' => 'Directorio y Redes'
        ]);
    }
}