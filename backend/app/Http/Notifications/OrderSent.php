<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderSent extends Notification implements ShouldQueue
{
    use Queueable;

    protected $username;
    protected $email;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($email, $username, $order)
    {
        $this->username = $username;
        $this->email = $email;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)
            ->view('mail.order-posted', [
                'username' => $this->username,
                'email' => $this->email,
                'order' => $this->order
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
