<?php
$title = 'Panel de Administración - Ingeniería Informática UNSXX';
$pageTitle = 'Panel de Administración';
$content = <<<'HTML'
<div class="mb-8">
    <h2 class="text-3xl font-bold text-unsxx-blue-900 mb-2">Panel de Administración</h2>
    <p class="text-unsxx-blue-600">Gestión completa del sistema</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <a href="/academics" class="group block p-6 bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm hover:shadow-lg hover:border-unsxx-yellow-300 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-unsxx-blue-100 group-hover:bg-unsxx-blue-500 transition-colors flex items-center justify-center">
                <svg class="w-7 h-7 text-unsxx-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM1.104 18.471A13.932 13.932 0 0112 16c2.5 0 4.847.655 6.896 1.746" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-unsxx-blue-500 font-medium">Plantel Docente</p>
                <p class="text-2xl font-bold text-unsxx-blue-900 group-hover:text-unsxx-blue-700 transition-colors">Académicos</p>
            </div>
        </div>
    </a>

    <a href="#" class="group block p-6 bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm hover:shadow-lg hover:border-unsxx-yellow-300 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-unsxx-yellow-100 group-hover:bg-unsxx-yellow-400 transition-colors flex items-center justify-center">
                <svg class="w-7 h-7 text-unsxx-yellow-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.572 1.065c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-unsxx-blue-500 font-medium">Laboratorios</p>
                <p class="text-2xl font-bold text-unsxx-blue-900">6 Activos</p>
            </div>
        </div>
    </a>

    <a href="#" class="group block p-6 bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm hover:shadow-lg hover:border-unsxx-yellow-300 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-green-100 group-hover:bg-green-500 transition-colors flex items-center justify-center">
                <svg class="w-7 h-7 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-unsxx-blue-500 font-medium">Extensión</p>
                <p class="text-2xl font-bold text-unsxx-blue-900">Eventos</p>
            </div>
        </div>
    </a>

    <a href="#" class="group block p-6 bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm hover:shadow-lg hover:border-unsxx-yellow-300 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-purple-100 group-hover:bg-purple-500 transition-colors flex items-center justify-center">
                <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-unsxx-blue-500 font-medium">Comunicados</p>
                <p class="text-2xl font-bold text-unsxx-blue-900">Publicados</p>
            </div>
        </div>
    </a>
</div>

<!-- Quick Actions -->
<div class="bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-unsxx-blue-900 mb-6">Acciones Rápidas</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="/academics/create" class="p-4 bg-unsxx-blue-50 border border-unsxx-blue-200 rounded-xl hover:bg-unsxx-blue-100 hover:border-unsxx-blue-300 transition-colors text-center">
            <svg class="w-6 h-6 text-unsxx-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <p class="font-medium text-unsxx-blue-800">Nuevo Académico</p>
        </a>
        <a href="#" class="p-4 bg-unsxx-yellow-50 border border-unsxx-yellow-200 rounded-xl hover:bg-unsxx-yellow-100 hover:border-unsxx-yellow-300 transition-colors text-center">
            <svg class="w-6 h-6 text-unsxx-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <p class="font-medium text-unsxx-blue-800">Nuevo Comunicado</p>
        </a>
        <a href="#" class="p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 hover:border-green-300 transition-colors text-center">
            <svg class="w-6 h-6 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            <p class="font-medium text-unsxx-blue-800">Subir Archivos</p>
        </a>
    </div>
</div>
HTML;
require __DIR__ . '/../layouts/app.php';