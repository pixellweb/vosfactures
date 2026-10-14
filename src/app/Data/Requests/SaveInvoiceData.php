<?php

namespace PixellWeb\Vosfactures\app\Data\Requests;

use Carbon\Carbon;
use Ipsum\Reservation\app\Models\Reservation\Facture;
use PixellWeb\Vosfactures\app\Ressources\Invoice;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;



class SaveInvoiceData extends Data
{
    public function __construct(

        public string $kind,
        //public string $status, // incomplete

        public bool $test,

        public int $department_id,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public Carbon $issue_date,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public Carbon $payment_to,

        public int $client_id,

        public int $show_discount,
        public string $discount_kind,


        /** @var array<array,InvoicePositionsData> */
        public array $positions,
    ) {
    }


    public static function fromIpsum(Facture $facture, bool $brouillon, Invoice $invoice = null): self
    {
        $dataCollection = [];

        if ($invoice) {
            // Pour mettre à jour un brouillon de facture, il faut aller chercher les ids des lignes de produit à créer, modifier
            $lines = collect($invoice->get($facture->provider_reference)->positions);

            foreach ($facture->produits as $produit) {
                $line = $lines->firstWhere('product_id', $produit->reference_externe);
                if ($line) {
                    $dataCollection[] = InvoicePositionsData::fromIpsum($produit)->toArray() + ['id' => $line['id']];
                } else {
                    $dataCollection[] = InvoicePositionsData::fromIpsum($produit)->toArray();
                }
            }

            // Pour supprimer un article sur la facture, entrez l'ID du produit avec le paramètre "_destroy" égal à 1.
            $refs = $facture->produits->pluck('reference_externe')->toArray();

            foreach ($lines as $line) {
                if (!in_array($line['product_id'], $refs, false)) {
                    $dataCollection[] = ['id' => $line['id'], '_destroy' => 1];
                }
            }

        } else {
            foreach ($facture->produits as $produit) {
                $dataCollection[] = InvoicePositionsData::fromIpsum($produit)->toArray();
            }
        }

        $has_remise = $facture->produits->contains(function ($value) {
            return $value->pivot->remise;
        });


        return self::validateAndCreate([
            'kind' => 'vat',
            'department_id' => config('vosfactures.department_id'),
            'issue_date' => now(),
            'payment_to' => $facture->echeance_at,
            'client_id' => $facture->client->reference_externe,
            'show_discount' => $has_remise ? 1 : 0,
            'discount_kind' => 'amount',
            'test' => $brouillon,
            'positions' => $dataCollection
        ]);
    }

    
}


