<?php

namespace PixellWeb\Vosfactures;


use Ipsum\Reservation\app\Events\PrestationCreatedEvent;
use Ipsum\Reservation\app\Events\PrestationUpdatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use PixellWeb\Vosfactures\app\Listeners\UpdatePrestation;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PrestationCreatedEvent::class => [
            UpdatePrestation::class
        ],
        PrestationUpdatedEvent::class => [
            UpdatePrestation::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
