@extends('layouts.admin')

@section('title', 'Panel administrativo - Gimnasio')
@section('page_title', 'Dashboard administrador')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded-xl shadow-sm">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">
                Usuarios
            </h2>
            <p class="text-2xl font-bold text-gray-800">
                Próximamente
            </p>
        </div>

        <div class="p-4 bg-white rounded-xl shadow-sm">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">
                Membresías
            </h2>
            <p class="text-2xl font-bold text-gray-800">
                Próximamente
            </p>
        </div>

        <div class="p-4 bg-white rounded-xl shadow-sm">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">
                Reportes
            </h2>
            <p class="text-2xl font-bold text-gray-800">
                Próximamente
            </p>
        </div>
    </div>
@endsection
