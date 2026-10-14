<?php

namespace PixellWeb\Vosfactures\app\Data\Responses;

use Spatie\LaravelData\Data;


class ClientData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $phone,
        public ?string $tva_number,
        public ?string $tax_no,
        public ?string $email,
        public ?string $external_id,

    ) {
    }

    
}
