<?php

namespace PixellWeb\Vosfactures\app\Data\Responses;

use Ipsum\Reservation\app\Models\Prestation\Tarification;
use Ipsum\Reservation\app\Models\Prestation\Taxe;
use Ipsum\Reservation\app\Models\Prestation\Type;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;


class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public string $price_gross,
        public string $tax,

    ) {
    }

    public function toIpsum(): array
    {
        $taxe = Taxe::where('taux', $this->tax)->firstOrFail();

        return [
            'reference_externe' => $this->id,
            'nom' => $this->name,
            'taxe_id' => $taxe->id,
            'montant' => $this->price_gross,
            'type_id' => Type::RESTITUTION_ID,
            'tarification_id' => Tarification::FORFAIT_ID,
        ];

    }
}
