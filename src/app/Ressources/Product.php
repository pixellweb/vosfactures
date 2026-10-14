<?php

namespace PixellWeb\Vosfactures\app\Ressources;




use PixellWeb\Vosfactures\app\Data\Requests\SaveProductData;
use PixellWeb\Vosfactures\app\Data\Responses\ProductData;

class Product extends Ressource
{

    public function list(): \Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection
    {
        $products = $this->crawler->get('products', ['per_page' => 100]);

        return ProductData::collection($products);

    }

    public function get(int $id): ProductData
    {
        $product = $this->crawler->get('products/'.$id);

        return ProductData::from($product);
    }


    public function create(SaveProductData $product_data): ProductData
    {
        $product = $this->crawler->post('products', ['product' => $product_data->toArray()]);

        return ProductData::from($product);
    }

    public function update(SaveProductData|array $product_data, int $product_id): ProductData
    {
        $data = is_object($product_data) ? $product_data->toArray() : $product_data;

        $product = $this->crawler->put('products/'.$product_id, ['product' => $data]);

        return ProductData::from($product);
    }

}