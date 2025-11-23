@extends('layouts.admin')

@section('title', 'Editar Usuario - FitGym')
@section('page_title', 'Editar Usuario')

@section('content')
    {{-- Breadcrumbs --}}
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.panel') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('admin.usuarios.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Usuarios</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Editar</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.usuarios.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contraseña (opcional) --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Nueva Contraseña (opcional)</label>
                    <input type="password" id="password" name="password"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                           placeholder="Dejar vacío para mantener la actual">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                           placeholder="Solo si cambias la contraseña">
                </div>

                {{-- Rol --}}
                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
                    <select id="role" name="role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                            required>
                        <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Cliente</option>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado Activo --}}
                <div class="flex items-center pt-8">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-900">Usuario Activo</label>
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.usuarios.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection

