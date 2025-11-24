@extends('layouts.admin')

@section('title', 'Membresías de Usuarios - FitGym')
@section('page_title', 'Membresías de Usuarios')

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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Membresías de Usuarios</span>
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

    {{-- Tabla de usuarios con sus membresías --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Usuario</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Membresías</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">Fechas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold mr-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @if($user->membresias_detalle && $user->membresias_detalle->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($user->membresias_detalle as $membresia)
                                            <div class="flex items-center gap-2">
                                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full
                                                    @if($membresia->activa && !$membresia->cancelada && \Carbon\Carbon::parse($membresia->fecha_fin)->isFuture())
                                                        bg-green-100 text-green-800
                                                    @elseif($membresia->cancelada)
                                                        bg-red-100 text-red-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $membresia->nombre }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    ${{ number_format($membresia->precio, 2) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sin membresías</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->membresias_detalle && $user->membresias_detalle->count() > 0)
                                    @php
                                        $membresiaActiva = $user->membresias_detalle->first(function($m) {
                                            return $m->activa && !$m->cancelada && \Carbon\Carbon::parse($m->fecha_fin)->isFuture();
                                        });
                                    @endphp
                                    @if($membresiaActiva)
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            Activa
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            Sin membresía activa
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        Sin membresía
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->membresias_detalle && $user->membresias_detalle->count() > 0)
                                    @php
                                        $membresiaActiva = $user->membresias_detalle->first(function($m) {
                                            return $m->activa && !$m->cancelada && \Carbon\Carbon::parse($m->fecha_fin)->isFuture();
                                        });
                                    @endphp
                                    @if($membresiaActiva)
                                        <div class="text-xs">
                                            <div class="text-gray-600">Inicio: {{ \Carbon\Carbon::parse($membresiaActiva->fecha_inicio)->format('d/m/Y') }}</div>
                                            <div class="text-gray-600">Fin: {{ \Carbon\Carbon::parse($membresiaActiva->fecha_fin)->format('d/m/Y') }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium">No hay usuarios registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $users->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $users->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $users->total() }}</span>
                        resultados
                    </div>
                    <div class="flex items-center gap-2">
                        @if($users->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">Anterior</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Anterior</a>
                        @endif

                        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if($page == $users->currentPage())
                                <span class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Siguiente</a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

