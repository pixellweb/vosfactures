<?php

namespace PixellWeb\Vosfactures\app\Console\Commands;



use Illuminate\Console\Command;
use Illuminate\Container\EntryNotFoundException;
use Ipsum\Reservation\app\Models\Prestation\Prestation;
use PixellWeb\Vosfactures\app\Actions\IpsumProductAction;
use PixellWeb\Vosfactures\app\Data\Responses\ProductData;
use PixellWeb\Vosfactures\app\Ressources\Product;


class Produit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vosfactures:produit {--import} {--export} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'synchronisation produit vosfactures';

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
     * @return int
     * @throws EntryNotFoundException
     */
    public function handle(IpsumProductAction $ipsumProductAction, Product $product): int
    {

        if ($this->option('import')) {
            try {
                $product->list()->each(function (ProductData $productData) use ($ipsumProductAction, $product) {

                    $ipsumProductAction->syncFromProvider($productData);

                    if ($this->option('force')) {
                        $product->update(['external_reference' => ''], $productData->id);
                    }
                });
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }


        if ($this->option('export')) {
            Prestation::whereNull('reference_externe')->get()->each(function (Prestation $prestation) use ($ipsumProductAction) {

                try {

                    $this->info('Import produit : ' . $prestation->nom);
                    $ipsumProductAction->syncToProvider($prestation);

                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }

            });
        }

        return self::SUCCESS;

    }





}
