<?php

namespace PixellWeb\Vosfactures\app\Ressources;



use PixellWeb\Vosfactures\app\Api;

abstract class Ressource
{

    public Api $crawler;


    /**
     * Ressource constructor.
     */
    public function __construct(?int $cache_time = null)
    {
        $this->crawler = new Api($cache_time);
    }

}
