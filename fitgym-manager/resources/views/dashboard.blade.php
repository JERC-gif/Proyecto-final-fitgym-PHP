<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @auth
                @if(auth()->user()->role === 'admin')
                    {{-- Dashboard para Admin --}}
                    <div class="mb-4 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-indigo-900">Panel de Administración disponible</span>
                            </div>
                            <a href="{{ route('admin.panel') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ir al Panel Admin
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'staff')
                    {{-- Dashboard para Staff - Redirigir al panel admin --}}
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-900">Panel de Estadísticas disponible</span>
                            </div>
                            <a href="{{ route('admin.panel') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ir al Panel
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Dashboard para Clientes --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-2xl font-bold text-gray-900">Bienvenido, {{ auth()->user()->name }}</h2>
                                    <p class="text-gray-600">Panel de Cliente - FitGym</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de tu cuenta</h3>
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Nombre:</span> {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Email:</span> {{ auth()->user()->email }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Rol:</span> 
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Cliente</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones disponibles</h3>
                                    <div class="space-y-3">
                                        <a href="{{ route('profile.show') }}" class="block w-full text-left px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            <span class="text-sm font-medium text-gray-700">Ver mi perfil</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-indigo-900 mb-2">¿Necesitas ayuda?</h3>
                                <p class="text-sm text-indigo-700">
                                    Si tienes alguna pregunta sobre tu membresía o necesitas asistencia, por favor contacta con nuestro equipo de soporte.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>
