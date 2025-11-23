<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- Mensajes de éxito/error --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            {{-- Sección de Membresía (solo para clientes) --}}
            @if(auth()->user()->role === 'client')
                <div class="mb-10">
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Mi Membresía</h3>
                            
                            @php
                                $membresiaActiva = auth()->user()->membresiaActiva();
                            @endphp

                            @if($membresiaActiva)
                                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                <h4 class="text-xl font-bold text-indigo-900">{{ $membresiaActiva->nombre }}</h4>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <p class="text-sm text-gray-600">Precio</p>
                                                    <p class="text-lg font-semibold text-gray-900">${{ number_format($membresiaActiva->precio, 2) }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Duración</p>
                                                    <p class="text-lg font-semibold text-gray-900">{{ $membresiaActiva->duracion_dias }} días</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Estado</p>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        Activa
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <p class="text-sm text-gray-600">Fecha de inicio</p>
                                                    <p class="text-base font-medium text-gray-900">
                                                        @php
                                                            $pivot = $membresiaActiva->pivot_data ?? null;
                                                        @endphp
                                                        {{ $pivot ? \Carbon\Carbon::parse($pivot->fecha_inicio)->format('d/m/Y') : 'N/A' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Fecha de vencimiento</p>
                                                    <p class="text-base font-medium text-gray-900">
                                                        @php
                                                            $pivot = $membresiaActiva->pivot_data ?? null;
                                                        @endphp
                                                        {{ $pivot ? \Carbon\Carbon::parse($pivot->fecha_fin)->format('d/m/Y') : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if($membresiaActiva->descripcion)
                                                <div class="mb-4">
                                                    <p class="text-sm text-gray-600 mb-1">Descripción</p>
                                                    <p class="text-sm text-gray-900">{{ $membresiaActiva->descripcion }}</p>
                                                </div>
                                            @endif

                                            <div class="mt-6 pt-4 border-t border-indigo-200">
                                                <form action="{{ route('profile.membresia.cancelar') }}" method="POST" 
                                                      onsubmit="return confirm('¿Estás seguro de cancelar tu membresía? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Cancelar Membresía
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- No tiene membresía activa - Mostrar opciones para adquirir --}}
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                    <div class="text-center mb-6">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No tienes una membresía activa</h4>
                                        <p class="text-sm text-gray-600 mb-6">
                                            Selecciona una membresía para comenzar:
                                        </p>
                                    </div>

                                    @php
                                        $membresiasDisponibles = \App\Models\Membresia::where('activa', true)->get();
                                    @endphp

                                    @if($membresiasDisponibles->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @foreach($membresiasDisponibles as $membresia)
                                                <div class="bg-white border border-gray-300 rounded-lg p-4 hover:border-indigo-500 hover:shadow-md transition-all">
                                                    <div class="text-center mb-4">
                                                        <h5 class="text-lg font-bold text-gray-900 mb-2">{{ $membresia->nombre }}</h5>
                                                        <p class="text-2xl font-bold text-indigo-600 mb-1">${{ number_format($membresia->precio, 2) }}</p>
                                                        <p class="text-sm text-gray-600">{{ $membresia->duracion_dias }} días</p>
                                                    </div>
                                                    
                                                    @if($membresia->descripcion)
                                                        <p class="text-xs text-gray-500 mb-4 text-center">{{ $membresia->descripcion }}</p>
                                                    @endif

                                                    <form action="{{ route('profile.membresia.adquirir') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="membresia_id" value="{{ $membresia->id }}">
                                                        <button type="submit" 
                                                                class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                                            Adquirir Membresía
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">
                                                No hay membresías disponibles en este momento. Contacta con nuestro equipo.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
