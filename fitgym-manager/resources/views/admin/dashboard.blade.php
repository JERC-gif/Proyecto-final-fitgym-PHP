@extends('layouts.admin')

@section('title', 'Panel administrativo - Gimnasio')
@section('page_title', auth()->user()->role === 'admin' ? 'Dashboard Administrador' : 'Dashboard Staff')

@section('content')
    {{-- Cards de estadísticas con Flowbite --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Card Usuarios (solo para admin) --}}
        @if(auth()->user()->role === 'admin')
            <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Clientes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalUsuarios }}</p>
                        <a href="{{ route('admin.usuarios.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 mt-2 inline-block">
                            Ver todos →
                        </a>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        {{-- Card Membresías Activas --}}
        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Membresías Activas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $membresiasActivas }}</p>
                    <p class="text-xs text-gray-500 mt-2">de {{ $totalMembresias }} totales</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card Total Membresías --}}
        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Membresías</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalMembresias }}</p>
                    <a href="{{ route('admin.membresias.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 mt-2 inline-block">
                        Ver todas →
                    </a>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card Usuarios Activos (solo para admin) --}}
        @if(auth()->user()->role === 'admin')
            <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Usuarios Activos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'client')->where('is_active', true)->count() }}</p>
                        <p class="text-xs text-gray-500 mt-2">Clientes habilitados</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sección de acciones rápidas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Card de acciones rápidas --}}
        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.usuarios.create') }}" class="w-full flex items-center justify-between p-4 text-sm font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-200 transition-colors">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                            </svg>
                            Crear nuevo usuario
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.membresias.create') }}" class="w-full flex items-center justify-between p-4 text-sm font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-200 transition-colors">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                            Nueva membresía
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
                
                <a href="{{ route('admin.membresias.index') }}" class="w-full flex items-center justify-between p-4 text-sm font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-200 transition-colors">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                        Ver todas las membresías
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.usuarios.index') }}" class="w-full flex items-center justify-between p-4 text-sm font-medium text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-200 transition-colors">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                            Ver todos los usuarios
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        {{-- Card de actividad reciente --}}
        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
            <div class="space-y-4">
                @if(auth()->user()->role === 'admin')
                    {{-- Usuarios recientes (solo para admin) --}}
                    @forelse($usuariosRecientes as $usuario)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $usuario->name }}</p>
                                <p class="text-xs text-gray-500">{{ $usuario->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No hay usuarios recientes</p>
                    @endforelse
                @endif
                
                {{-- Membresías recientes (para admin y staff) --}}
                @forelse($membresiasRecientes as $membresia)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Membresía: {{ $membresia->nombre }}</p>
                            <p class="text-xs text-gray-500">{{ $membresia->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No hay membresías recientes</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

