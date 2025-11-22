<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel administrativo - Gimnasio')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">

<div class="min-h-screen flex flex-col">

    {{-- Navbar superior con Flowbite --}}
    <nav class="bg-white border-b border-gray-200 px-4 py-3 fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                {{-- Botón hamburger para móviles --}}
                <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Abrir menú</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <span class="text-2xl font-bold text-indigo-600">FitGym</span>
                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded hidden md:inline">Panel Admin</span>
            </div>

            <div class="flex items-center space-x-4">
                {{-- Dropdown de usuario con Flowbite --}}
                <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser"
                        class="flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100"
                        type="button">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="font-medium">{{ auth()->user()->name ?? 'Invitado' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>

                {{-- Dropdown menu --}}
                <div id="dropdownUser" class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 font-semibold">{{ auth()->user()->name ?? 'Invitado' }}</span>
                        <span class="block text-sm text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="dropdownUserButton">
                        <li>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Dashboard General
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">

        {{-- Sidebar mejorado con Flowbite --}}
        <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0" aria-label="Sidebar">
            <div class="h-full px-3 py-4 overflow-y-auto">
                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="{{ route('admin.panel') }}"
                           class="flex items-center p-3 text-gray-900 rounded-lg
                                  @if(request()->routeIs('admin.panel')) bg-indigo-100 text-indigo-700 @else hover:bg-gray-100 @endif group">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 @if(request()->routeIs('admin.panel')) text-indigo-600 @endif"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                            <span class="ms-3">Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ms-3">Membresías</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ms-3">Reportes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ms-3">Configuración</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <main class="flex-1 md:ml-64 p-4 md:p-6 pt-20">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    @yield('page_title', 'Dashboard')
                </h1>
                <p class="text-gray-600 mt-1">Bienvenido al panel de administración</p>
            </div>

            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
