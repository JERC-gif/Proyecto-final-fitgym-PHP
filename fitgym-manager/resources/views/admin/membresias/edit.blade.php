@extends('layouts.admin')

@section('title', 'Editar Membresía - FitGym')
@section('page_title', 'Editar Membresía')

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
                    <a href="{{ route('admin.membresias.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Membresías</a>
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
        <form method="POST" action="{{ route('admin.membresias.update', $membresia->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tipo de Membresía (Select) --}}
                <div>
                    <label for="tipo_membresia" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Membresía</label>
                    <select id="tipo_membresia" name="tipo_membresia"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                            required>
                        <option value="">Seleccione un tipo</option>
                        <option value="Visita" {{ old('tipo_membresia', $membresia->nombre) == 'Visita' ? 'selected' : '' }}>Visita</option>
                        <option value="Semana" {{ old('tipo_membresia', $membresia->nombre) == 'Semana' ? 'selected' : '' }}>Semana</option>
                        <option value="Mes" {{ old('tipo_membresia', $membresia->nombre) == 'Mes' ? 'selected' : '' }}>Mes</option>
                    </select>
                    <input type="hidden" id="nombre" name="nombre" value="{{ old('nombre', $membresia->nombre) }}" required>
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Precio --}}
                <div>
                    <label for="precio" class="block mb-2 text-sm font-medium text-gray-900">Precio</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input type="number" id="precio" name="precio" value="{{ old('precio', $membresia->precio) }}" step="0.01" min="0"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-8 p-2.5"
                               required>
                    </div>
                    @error('precio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duración en días --}}
                <div>
                    <label for="duracion_dias" class="block mb-2 text-sm font-medium text-gray-900">Duración (días)</label>
                    <input type="number" id="duracion_dias" name="duracion_dias" value="{{ old('duracion_dias', $membresia->duracion_dias) }}" min="1"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                           required>
                    @error('duracion_dias')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado Activa --}}
                <div class="flex items-center pt-8">
                    <input id="activa" name="activa" type="checkbox" value="1" {{ old('activa', $membresia->activa) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="activa" class="ml-2 text-sm font-medium text-gray-900">Membresía Activa</label>
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900">Descripción (opcional)</label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">{{ old('descripcion', $membresia->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.membresias.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Actualizar Membresía
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoSelect = document.getElementById('tipo_membresia');
            const nombreInput = document.getElementById('nombre');
            const precioInput = document.getElementById('precio');
            const duracionInput = document.getElementById('duracion_dias');

            // Configuración de precios y días
            const configuracion = {
                'Visita': { precio: 50, dias: 1 },
                'Semana': { precio: 250, dias: 7 },
                'Mes': { precio: 500, dias: 30 }
            };

            tipoSelect.addEventListener('change', function() {
                const tipo = this.value;
                
                if (tipo && configuracion[tipo]) {
                    // Actualizar nombre
                    nombreInput.value = tipo;
                    
                    // Actualizar precio y días
                    precioInput.value = configuracion[tipo].precio;
                    duracionInput.value = configuracion[tipo].dias;
                } else {
                    // Limpiar si no hay selección
                    nombreInput.value = '';
                    precioInput.value = '';
                    duracionInput.value = '';
                }
            });

            // Si hay un valor, actualizar al cargar
            if (tipoSelect.value) {
                tipoSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection

