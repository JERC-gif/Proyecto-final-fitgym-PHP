<?php

/**
 * Service Provider para Laravel Fortify (Autenticación)
 *
 * Este provider configura:
 * - Acciones de autenticación (crear usuario, actualizar perfil, etc.)
 * - Redirecciones después de login/registro según el rol del usuario
 * - Rate limiting para login y autenticación de dos factores
 *
 * IMPORTANTE: Redirecciones basadas en roles
 * - Admin → /admin/panel
 * - Otros roles → /dashboard
 */

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        /**
         * Configurar redirección después del login exitoso
         *
         * Lógica:
         * - Si el usuario es admin → redirige a /admin/panel
         * - Si el usuario es staff → redirige a /admin/panel (dashboard de estadísticas)
         * - Si el usuario es client → redirige a /dashboard (panel de cliente)
         */
        $this->app->singleton(\Laravel\Fortify\Contracts\LoginResponse::class, function () {
            return new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request) {
                    // Redirigir según el rol del usuario
                    $user = Auth::user();
                    if ($user && ($user->role === 'admin' || $user->role === 'staff')) {
                        return redirect()->route('admin.panel');
                    }
                    // Clientes van al dashboard
                    return redirect()->route('dashboard');
                }
            };
        });

        /**
         * Configurar redirección después del registro exitoso
         *
         * Lógica:
         * - Si el usuario es admin → redirige a /admin/panel
         * - Si el usuario es staff → redirige a /admin/panel (dashboard de estadísticas)
         * - Si el usuario es client → redirige a /dashboard (panel de cliente)
         *
         * Nota: Los nuevos usuarios se crean con rol 'client' por defecto
         * (ver: app/Actions/Fortify/CreateNewUser.php)
         */
        $this->app->singleton(\Laravel\Fortify\Contracts\RegisterResponse::class, function () {
            return new class implements \Laravel\Fortify\Contracts\RegisterResponse {
                public function toResponse($request) {
                    // Redirigir según el rol del usuario
                    $user = Auth::user();
                    if ($user && ($user->role === 'admin' || $user->role === 'staff')) {
                        return redirect()->route('admin.panel');
                    }
                    // Clientes van al dashboard
                    return redirect()->route('dashboard');
                }
            };
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
