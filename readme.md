## Notes

## Mise en place

1) Paramètres > API ; Générer le token
2) Renseigner dans le fichier .env

        FACTURE_PROVIDER=vosfactures
        VOSFACTURES_TOKEN=xxxxxxxx

3) Lancer la commande pour envoyer les produits dans Vosfactures

        php artisan vosfactures:produit --export 

4) Ajouter la commande dans le Kernel.

        $schedule->command('vosfactures:send-invoice')->daily();
5) Paramétrer l'envoi d'email dans les paramétres de vosfactures.fr/

## Doc api 

- https://github.com/vosfactures/api
- https://pixell.vosfactures.fr/api





