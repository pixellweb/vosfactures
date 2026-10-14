<?php

namespace PixellWeb\Vosfactures\app\Data\Requests;


use Ipsum\Reservation\app\Models\Prestation\Prestation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;


class InvoicePositionsData extends Data
{
    public function __construct(
        public int $product_id,
        public string $name,
        public ?string $description,
        public ?string $discount,
        public int $quantity,
        public string $tax,
        public string $total_price_gross,
    ) {
    }


    public static function fromIpsum(Prestation $prestation): self
    {
        return self::validateAndCreate([
            'product_id' => $prestation->reference_externe,
            'name' => $prestation->nom,
            'description' => $prestation->pivot->description ?? null,
            'total_price_gross' => (string) ($prestation->pivot->montant + $prestation->pivot->remise),
            'tax' => $prestation->taxe->taux,
            'quantity' => $prestation->pivot->quantite,
            'discount' => $prestation->pivot->remise,
        ]);
    }
}
