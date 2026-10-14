<?php

namespace PixellWeb\Vosfactures\app\Ressources;



use PixellWeb\Vosfactures\app\Data\Requests\SaveClientData;
use PixellWeb\Vosfactures\app\Data\Responses\ClientData;

class Client extends Ressource
{

    public function list(): \Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection
    {
        $customers = $this->crawler->get('clients');

        return ClientData::collection($customers['items']);
    }

    public function get(int $id): ClientData
    {
        $customer = $this->crawler->get('clients/'.$id);

        return ClientData::from($customer);
    }

    public function create(SaveClientData $customer_data): ClientData
    {
        $customer = $this->crawler->post('clients', ['client' => $customer_data->toArray()]);

        return ClientData::from($customer);
    }

    public function update(SaveClientData $customer_data, int $customer_id): ClientData
    {
        $customer = $this->crawler->put('clients/'.$customer_id, ['client' => $customer_data->toArray()]);

        return ClientData::from($customer);
    }

}