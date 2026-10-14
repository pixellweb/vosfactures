<?php

namespace PixellWeb\Vosfactures\app\Actions;


use Illuminate\Support\Facades\Cache;
use Ipsum\Reservation\app\Models\Reservation\Facture;
use PixellWeb\Vosfactures\app\Data\Requests\SaveInvoiceData;
use PixellWeb\Vosfactures\app\Data\Responses\InvoiceData;
use PixellWeb\Vosfactures\app\VosfacturesException;
use PixellWeb\Vosfactures\app\Ressources\Invoice;

class IpsumInvoiceAction
{
    public function __construct(
        private Invoice $invoice
    ) {}


    public function syncToProvider(Facture $facture, bool $brouillon): ?InvoiceData
    {
        if ($facture->provider_reference) {

            if ($facture->provider !== 'vosfactures') {
                throw new VosfacturesException('Provider reference not allowed');
            }

            if (!$facture->numero) {
                $invoice = $this->invoice->update(SaveInvoiceData::fromIpsum($facture, $brouillon, $this->invoice), $facture->provider_reference);
            }

        } else {
            $invoice = $this->invoice->create(SaveInvoiceData::fromIpsum($facture, $brouillon));

            $facture->update($invoice->toIpsum());
        }

        if ($facture->numero and $facture->is_payee) {
            $this->invoice->markAsPaid($facture->provider_reference);
        }

        return $invoice ?? null;
    }

    public function emmission(Facture $facture): void
    {
        /*
         * Pas de brouillon chez vosfactures. On passe par une création d'une facture en mode test qu'il faut supprimer au moment de l'émission
         */
        $facture_a_supprimer = $facture->provider_reference;
        $invoiceData = $this->invoice->create(SaveInvoiceData::fromIpsum($facture, false));
        $facture->update($invoiceData->toIpsum());

        $this->invoice->delete($facture_a_supprimer);

        if ($facture->numero and $facture->is_payee) {
            $this->invoice->markAsPaid($facture->provider_reference);
        }
    }

    public function delete(Facture $facture): void
    {
        $this->invoice->delete($facture->provider_reference);
    }


    public function sendToCustomer(Facture $facture, bool|string $email = null): void
    {
        if (app()->isLocal() or app()->hasDebugModeEnabled()) {
            return;
        }

        if ($email === true) {
            $email = null;
        }

        $this->invoice->sendByEmail($facture->provider_reference, $email);


        /*if (!$facture->client->is_entreprise or $email) {
            if ($email === true) {
                $email = null;
            }

            $this->invoice->sendByEmail($facture->provider_reference, $email);

        } else {
            $this->sendToCustomer($facture, true);

            try {
                $this->invoice->sendToPA($facture->provider_reference);
            } catch (\Exception $exception) {
                if ($exception->getCode() === 422) {
                    $this->sendToCustomer($facture, true);
                }
            }
        }*/

        $facture->send_at = now();
        $facture->save();
    }

    public function getUrlPdf(Facture $facture, $cache = true): string
    {
        if (!$facture->provider_reference) {
            throw new VosfacturesException('Réfèrence Vosfactures non renseignée.');
        }

        if (!$cache) {
            return $this->invoice->get($facture->provider_reference)->view_url;
        }

        return Cache::remember('facture-'.$facture->numero, 5 * 60, function () use ($facture) {
            return $this->invoice->get($facture->provider_reference)->view_url;
        });
    }
}


