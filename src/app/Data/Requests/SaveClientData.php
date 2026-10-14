<?php

namespace PixellWeb\Vosfactures\app\Data\Requests;

use Ipsum\Reservation\app\Models\Reservation\Facture;
use Spatie\LaravelData\Data;


class SaveClientData extends Data
{
    public function __construct(
        public string $name,
        public string $first_name,
        public string $last_name,
        public bool $company,
        public string $phone,
        public string $post_code,
        public string $city,
        public string $street,
        public string $country,
        public ?string $tax_no_kind,
        public ?string $tax_no,
        public ?string $tva_number,
        public string $external_id,
        public string $email,


    ) {
    }

    public static function fromIpsum(Facture $facture): self
    {
        if ($facture->client->is_entreprise) {
            return self::validateAndCreate([
                'name' => $facture->client->nom,
                'company' => true,
                'phone' => $facture->client->telephone,
                'post_code' => $facture->client->cp,
                'city' => $facture->client->ville,
                'street' => $facture->client->adresse,
                'country' => $facture->client->pays?->alpha2,
                'tax_no_kind' => 'SIREN',
                'tax_no' => $facture->client->siren,
                'tva_number' => $facture->client->vat_numero,
                'external_id' => $facture->client->code,
                'email' => $facture->client->email
            ]);
        }

        return self::validateAndCreate([
            'name' => $facture->reservation->prenom.' '.$facture->reservation->nom,
            'first_name' => $facture->reservation->prenom,
            'last_name' => $facture->reservation->nom,
            'company' => false,
            'phone' => $facture->reservation->telephone,
            'post_code' => $facture->reservation->cp,
            'city' => $facture->reservation->ville,
            'street' => $facture->reservation->adresse,
            'country' => $facture->reservation->pays?->alpha2,
            'external_id' => $facture->client->code,
            'email' => $facture->reservation->email
        ]);
    }
}
