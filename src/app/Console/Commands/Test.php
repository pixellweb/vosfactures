<?php

namespace PixellWeb\Vosfactures\app\Console\Commands;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Container\EntryNotFoundException;
use Ipsum\Reservation\app\Models\Prestation\Prestation;
use Ipsum\Reservation\app\Models\Reservation\Facture;
use PixellWeb\Pennylane\app\Data\Responses\ProductData;
use PixellWeb\Vosfactures\app\Ressources\Product;
use PixellWeb\Vosfactures\app\Data\Requests\SaveInvoiceData;
use PixellWeb\Vosfactures\app\Data\Requests\SaveProductData;
use PixellWeb\Vosfactures\app\Data\Responses\InvoiceData;
use PixellWeb\Vosfactures\app\Ressources\Client;
use PixellWeb\Vosfactures\app\Ressources\Invoice;


class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vosfactures:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test vosfactures';

    protected $browser;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws EntryNotFoundException
     */
    public function handle(
        Invoice $invoice,
        Client $client,
        Product $product,
        /*Customer $customer,
        IpsumCustomerAction $ipsumCustomerAction,
        IpsumInvoiceAction $ipsumInvoiceAction,*/
    ): void
    {

        dd($product->update(['name' => 'jjjj'], 9005814512724));

        dd($product->get(9005814510684));


        $productData = SaveProductData::fromIpsum(Prestation::find(1));
        //dd($productData);
        dd($product->create($productData));


        $data = [

            'client_id' => 276519246,

            "kind"=>"vat",
            "number"=> null,
            "sell_date"=> "2026-09-03",
            "issue_date"=> "2026-09-03",
            "payment_to"=> "2026-09-10",
            "seller_name"=> "Seller SA",
            "seller_tax_no"=> "6272616681",
            "buyer_name"=> "Client1 SA",
            "buyer_tax_no"=> "6272616681",
            "positions"=>[
                ["name"=>"Produkt A1", "tax"=>23, "total_price_gross"=>10.23, "quantity"=>1],
                ["name"=>"Produkt A2", "tax"=>0, "total_price_gross"=>50, "quantity"=>2]
            ]
        ];

        Facture::find(3);
        $invoiceData = SaveInvoiceData::fromIpsum(Facture::find(3), true);
        dd($invoice->create($invoiceData));

        dd(SaveInvoiceData::from($data));

        dd($invoice->create(InvoiceData::from($data))->toArray());

        dd($invoice->list());

        $invoice = $invoice->get(558500073);
        dd($invoice->created_at->format('Y-m-d H:i:s'));

        $me = new Me();
        dd($me->get());

    }





}
