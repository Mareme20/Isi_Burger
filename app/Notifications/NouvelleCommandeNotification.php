<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleCommandeNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Commande $commande)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $commandeId = $this->commande->id;
        $total = number_format((float) $this->commande->total, 0, ',', ' ');
        $client = $this->commande->user?->name ?? 'Client';

        return (new MailMessage)
            ->subject("Nouvelle commande #{$commandeId} - ISI BURGER")
            ->greeting('Bonjour,')
            ->line("Une nouvelle commande vient d'etre enregistree par {$client}.")
            ->line("Montant total: {$total} FCFA")
            ->action('Voir la commande', route('admin.commandes.show', $this->commande))
            ->line('Merci de la traiter rapidement.');
    }
}

