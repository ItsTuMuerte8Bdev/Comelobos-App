<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Http\Events\RequestHandled;

class AppServiceProvider extends ServiceProvider
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
        // ================================================================
        // SOLUCIÓN BUG-007: Escudo Anti-Caché para el botón "Atrás"
        // ================================================================
        // Esto intercepta la respuesta justo antes de enviarla al navegador
        // y le prohíbe guardar "fotos fantasma" en el historial.
        
        Event::listen(RequestHandled::class, function (RequestHandled $event) {
            
            // Verificamos que la respuesta sea modificable (por si es una descarga de archivo)
            if (method_exists($event->response, 'header')) {
                $event->response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $event->response->header('Pragma', 'no-cache');
                $event->response->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
            }
            
        });
    }
}