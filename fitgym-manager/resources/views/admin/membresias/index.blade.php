@extends('layouts.admin')

@section('title', 'Gestión de Membresías - FitGym')
@section('page_title', 'Membresías')

@section('content')
    {{-- Breadcrumbs --}}
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.panel') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        Dashboard
                    </a>
                @endif
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Membresías</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Barra de acciones y búsqueda --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input type="text" id="search" 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                       placeholder="Buscar membresías...">
            </div>
        </div>
        <div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.membresias.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Membresía
                </a>
            @endif
        </div>
    </div>

    {{-- Tabla de membresías --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Descripción</th>
                        <th scope="col" class="px-6 py-3">Precio</th>
                        <th scope="col" class="px-6 py-3">Duración (días)</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        @if(auth()->user()->role === 'admin')
                            <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($membresias as $membresia)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $membresia->nombre }}
                            </td>
                            <td class="px-6 py-4">
                                {{ Str::limit($membresia->descripcion ?? 'Sin descripción', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${{ number_format($membresia->precio, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $membresia->duracion_dias }} días
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full
                                    @if($membresia->activa) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $membresia->activa ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.membresias.edit', $membresia->id) }}"
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.membresias.destroy', $membresia->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta membresía? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Eliminar">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Solo lectura</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '6' : '5' }}" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm font-medium">No hay membresías registradas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($membresias->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando 
                        <span class="font-medium">{{ $membresias->firstItem() }}</span>
                        a 
                        <span class="font-medium">{{ $membresias->lastItem() }}</span>
                        de 
                        <span class="font-medium">{{ $membresias->total() }}</span>
                        resultados
                    </div>
                    <div class="flex items-center gap-2">
                        @if($membresias->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">Anterior</span>
                        @else
                            <a href="{{ $membresias->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Anterior</a>
                        @endif
                        
                        @foreach($membresias->getUrlRange(1, $membresias->lastPage()) as $page => $url)
                            @if($page == $membresias->currentPage())
                                <span class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($membresias->hasMorePages())
                            <a href="{{ $membresias->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Siguiente</a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

