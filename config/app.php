<?php
// config/app.php (In cima al file, con gli altri 'use')
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade; // Aggiunto per gli aliases

return [

    // ... (Tutte le sezioni che hai incollato: 'name', 'env', 'debug', 'url', 'timezone', ecc.) ...

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Service Providers (SEZIONE AGGIUNTA)
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. This is where RouteServiceProvider MUST be.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        // App\Providers\AuthServiceProvider::class,
        // App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class, // <-- QUI REGISTRIAMO IL COMPONENTE DI ROUTING!

    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases (SEZIONE AGGIUNTA)
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class, // Inserisci i tuoi Facades personalizzati qui
    ])->toArray(),

]; // Chiusura dell'array 'return'