<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPaiementEleveNotification extends Notification
{
    use Queueable;
    public $paiement;

    /**
     * Create a new notification instance.
     */
    public function __construct($paiement)
    {
        //
        $this->paiement = $paiement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
       $username = $this->paiement->inscription->eleve->prenom . " " . $this->paiement->inscription->eleve->nom;
        $montantPayer = number_format($this->paiement->montantPayer, 0, ',', ' ');
        $classe = $this->paiement->inscription->classe->nom;

        return [
            'paiement_id' => $this->paiement->id,
            'message' => "💰 Paiement de {$montantPayer} F effectué par {$username} de la classe de {$classe}."
        ];


    }
}
