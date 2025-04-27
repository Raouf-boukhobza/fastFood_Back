<?php

namespace App\Notifications;

use App\Models\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiringProductNotification extends Notification
{
    use Queueable;

    protected $product;

    /**
     * Create a new notification instance.
     */
    public function __construct(Products $product)
    {
        $this->product = $product;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'expiry_date' => $this->product->expiration_date,
            'message' => 'The product ' . $this->product->name . ' is expiring soon.',
        ];
    }
}
