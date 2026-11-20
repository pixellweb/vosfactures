<?php

namespace PixellWeb\Vosfactures\app\Console\Commands;



use Illuminate\Console\Command;
use Illuminate\Container\EntryNotFoundException;
use Ipsum\Reservation\app\Enum\FactureEtat;
use Ipsum\Reservation\app\Models\Reservation\Facture;
use PixellWeb\Vosfactures\app\FactureVosfacturesProvider;


class SendToCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vosfactures:send-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoi Vosfactures des factures au client';

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
    public function handle(FactureVosfacturesProvider $factureVosfacturesProvider): int
    {
        $factures = Facture::whereNull('send_at')
            ->whereNotNull('numero')
            ->where('etat', '!=', FactureEtat::BROUILLON)
            ->where('emission_at', '>', now()->subDays(10)) // Permet de ne pas envoyer de trop vielles facture
            ->get();

        $factures->each(function (Facture $facture) use ($factureVosfacturesProvider) {
            $this->info('Envoi facture '.$facture->numero);
            try {
                $factureVosfacturesProvider->sendToCustomer($facture);
            } catch (\Exception $exception) {
                $this->alert($exception->getMessage());
            }
        });


        return self::SUCCESS;

    }





}
