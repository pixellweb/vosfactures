<?php

namespace PixellWeb\Vosfactures\app\Ressources;




use PixellWeb\Vosfactures\app\Enums\Status;
use PixellWeb\Vosfactures\app\Data\Requests\SaveInvoiceData;
use PixellWeb\Vosfactures\app\Data\Responses\InvoiceData;

class Invoice extends Ressource
{

    public function list(): \Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection
    {
        $invoices = $this->crawler->get('invoices');

        return InvoiceData::collection($invoices);
    }

    public function get(int $id): InvoiceData
    {
        $invoice = $this->crawler->get('invoices/'.$id);

        return InvoiceData::from($invoice);
    }

    public function create(SaveInvoiceData $invoice_data): InvoiceData
    {
        $invoice = $this->crawler->post('invoices', ['invoice' => $invoice_data->toArray()]);

        return InvoiceData::from($invoice);
    }

    public function update(SaveInvoiceData|array $invoice_data, int $invoice_id): InvoiceData
    {
        $data = is_object($invoice_data) ? $invoice_data->toArray() : $invoice_data;

        $invoice = $this->crawler->put('invoices/'.$invoice_id, ['invoice' => $data]);

        return InvoiceData::from($invoice);
    }

    public function delete(int $id): void
    {
        $this->crawler->delete('invoices/'.$id);
    }

    public function sendByEmail(int $id, string $email = null)
    {
        return $this->crawler->post('invoices/'.$id.'/send_by_email', [
            'update_buyer_email' => $email,
            'email_pdf' => true,
            'force' => true
        ]);
    }

    /*
     * Envoi E-facture
     */
    public function sendToPA(int $id)
    {
        // TODO semble n'être pris en compte qu'a partir du 01/09/2027
        //return $this->crawler->post('invoices/'.$id.'/send_to_pa');
    }

    public function finalize(int $id): InvoiceData
    {
        $invoice = $this->crawler->post('invoices', ['invoice' => ['copy_invoice_from' => $id, 'kind' => 'vat']]);
        $this->crawler->delete('invoices/'.$id);

        return InvoiceData::from($invoice);
    }

    public function markAsPaid(int $id): void
    {
        $this->crawler->post('invoices/'.$id.'/change_status', ['status' => Status::PAID]);
    }

}