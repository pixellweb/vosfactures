<?php

namespace PixellWeb\Vosfactures\app;


use Illuminate\View\View;
use Ipsum\Reservation\app\Contracts\FactureContract;
use Ipsum\Reservation\app\Models\Reservation\Facture;
use PixellWeb\Vosfactures\app\Actions\IpsumClientAction;
use PixellWeb\Vosfactures\app\Actions\IpsumCustomerAction;
use PixellWeb\Vosfactures\app\Actions\IpsumInvoiceAction;

class FactureVosfacturesProvider implements FactureContract
{

    public function __construct(
        private IpsumInvoiceAction $invoiceAction,
        private IpsumClientAction $clientAction,
    ) {}

    public function syncToProvider(Facture $facture, bool $brouillon = true): void
    {
        $this->clientAction->syncToProvider($facture);
        $this->invoiceAction->syncToProvider($facture, $brouillon);
    }

    public function emmission(Facture $facture): void
    {
        $this->invoiceAction->emmission($facture);
    }

    public function sendToCustomer(Facture $facture, bool|string $email = null): void
    {
        $this->invoiceAction->sendToCustomer($facture, $email);
    }

    public function delete(Facture $facture): void
    {
        $this->invoiceAction->delete($facture);
    }

    public function getUrlPdf(Facture $facture, $cache = true): string
    {
        return $this->invoiceAction->getUrlPdf($facture, $cache);
    }

    public function iframe(): View
    {
        return view('Vosfactures::iframe');
    }
}