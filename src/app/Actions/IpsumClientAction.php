<?php

namespace PixellWeb\Vosfactures\app\Actions;


use Ipsum\Reservation\app\Models\Reservation\Facture;
use PixellWeb\Vosfactures\app\Data\Requests\SaveClientData;
use PixellWeb\Vosfactures\app\Data\Responses\ClientData;
use PixellWeb\Vosfactures\app\Ressources\Client;

class IpsumClientAction
{
    public function __construct(
        private Client $client,
    ) {}



    public function syncToProvider(Facture $facture): ClientData
    {
        $saveCustomerData = SaveClientData::fromIpsum($facture);

        $is_create = !$facture->client->reference_externe;

        if ($is_create) {
            $customer = $this->client->create($saveCustomerData);

            $facture->client->reference_externe = $customer->id;
            $facture->client->save();

        } else {
            $customer = $this->client->update($saveCustomerData, $facture->client->reference_externe);
        }

        return $customer;
    }

}


