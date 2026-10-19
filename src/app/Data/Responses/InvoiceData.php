<?php

namespace PixellWeb\Vosfactures\app\Data\Responses;

use Carbon\Carbon;
use Ipsum\Reservation\app\Enum\FactureEtat;
use PixellWeb\Vosfactures\app\Enums\Status;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;


class InvoiceData extends Data
{
    public function __construct(
        public int $id,
        public ?string $number,
        public string $kind,
        #[WithCast(EnumCast::class, type:Status::class)]
        public Status $status,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public Carbon $issue_date,

        public string $price_gross,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public Carbon $payment_to,


        public string $view_url,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public Carbon $created_at,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public Carbon $updated_at,

        public ?array $positions,

    ) {
    }


    public function toIpsum(): ?array
    {
        $is_brouillon = str_contains($this->number, '_TEST');



        // Actuelement seulement la modification prise en compte
        return [
            'numero' => $is_brouillon ? null : $this->number,
            'etat' => $is_brouillon ? FactureEtat::BROUILLON : ($this->status === Status::PAID ? FactureEtat::PAYEE : FactureEtat::ENCOURS),
            'provider' => 'vosfactures',
            'provider_reference' => $this->id,
            'emission_at' => $this->issue_date
        ];
        
    }


    
}
