<?php

namespace PixellWeb\Vosfactures;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Ipsum\Reservation\app\Contracts\FactureContract;
use PixellWeb\Vosfactures\app\Console\Commands\Produit;
use PixellWeb\Vosfactures\app\Console\Commands\SendToCustomer;
use PixellWeb\Vosfactures\app\Console\Commands\Test;
use PixellWeb\Vosfactures\app\FactureVosfacturesProvider;



class VosfacturesServiceProvider extends ServiceProvider
{

    protected $commands = [
        Test::class,
        Produit::class,
        /*SendToCustomer::class,*/
    ];


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__.'/ressources/views', 'Vosfactures');

        $this->addCustomConfigurationValues();

        /*Route::middleware(['web'])
            ->prefix(config('ipsum.admin.route_prefix'))
            ->group(__DIR__.'/routes/admin.php');*/
    }

    public function addCustomConfigurationValues()
    {
        // add filesystems.disks for the log viewer
        config([
            'logging.channels.vosfactures' => [
                'driver' => 'single',
                'path' => storage_path('logs/vosfactures.log'),
                'level' => 'debug',
            ]
        ]);

    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/vosfactures.php', 'vosfactures'
        );

        // register the artisan commands
        $this->commands($this->commands);

        if (config('ipsum.reservation.facture_provider') === 'vosfactures') {
            $this->app->bind(FactureContract::class, FactureVosfacturesProvider::class);
        }
    }
}
