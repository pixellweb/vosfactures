<?php

namespace PixellWeb\Vosfactures\app\Data\Requests;

use Ipsum\Reservation\app\Models\Prestation\Prestation;
use Spatie\LaravelData\Data;


class SaveProductData extends Data
{
    public function __construct(
        public string $name,
        public ?string $price_gross,
        public string $tax,
        public bool $service,

    ) {
    }


    public static function fromIpsum(Prestation $prestation): self
    {

        return self::validateAndCreate([
            'name' => $prestation->nom,
            'price_gross' => (string) $prestation->montant,
            'tax' => $prestation->taxe->taux,
            'service' => true,
        ]);
    }
}
