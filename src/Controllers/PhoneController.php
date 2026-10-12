<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Phone;

class PhoneController extends Controller
{
    private Phone $model;

    public function __construct()
    {
        $this->model = new Phone();
    }

    /**
     * Formulario de creación
     * GET /directory/phones/create
     */
    public function create(): void
    {
        $this->view('directory/phone_create', [
            'title' => 'Nuevo Celular',
            'pageTitle' => 'Nuevo Celular'
        ]);
    }

    /**
     * Guarda un nuevo celular
     * POST /directory/phones
     */
    public function store(): void
    {
        $data = [
            'nom_cel' => trim($_POST['nom_cel'] ?? ''),
            'num_cel' => trim($_POST['num_cel'] ?? ''),
            'icono' => trim($_POST['icono'] ?? 'fab fa-whatsapp'),
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Celular creado correctamente (ID: {$id})";
            header('Location: /directory');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('directory/phone_create', [
                'title' => 'Nuevo Celular',
                'pageTitle' => 'Nuevo Celular',
                'old' => $data
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /directory/phones/{id}/edit
     */
    public function edit(int $id): void
    {
        $phone = $this->model->findById($id);

        if (!$phone) {
            $_SESSION['error'] = 'Celular no encontrado';
            header('Location: /directory');
            exit;
        }

        $this->view('directory/phone_edit', [
            'phone' => $phone,
            'title' => 'Editar Celular',
            'pageTitle' => 'Editar Celular'
        ]);
    }

    /**
     * Actualiza un celular
     * POST /directory/phones/{id}/edit
     * POST /directory/phones/{id}
     */
    public function update(int $id): void
    {
        $data = [
            'nom_cel' => trim($_POST['nom_cel'] ?? ''),
            'num_cel' => trim($_POST['num_cel'] ?? ''),
            'icono' => trim($_POST['icono'] ?? 'fab fa-whatsapp'),
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Celular actualizado correctamente';
            header('Location: /directory');
            exit;
        } catch (\Exception $e) {
            $phone = $this->model->findById($id);
            $_SESSION['error'] = $e->getMessage();
            $this->view('directory/phone_edit', [
                'phone' => $phone,
                'title' => 'Editar Celular',
                'pageTitle' => 'Editar Celular',
                'old' => $data
            ]);
        }
    }

    /**
     * Elimina un celular
     * POST /directory/phones/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $deleted = $this->model->delete($id);

            if ($deleted) {
                $_SESSION['success'] = 'Celular eliminado correctamente';
            } else {
                $_SESSION['error'] = 'El celular no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /directory');
        exit;
    }
}