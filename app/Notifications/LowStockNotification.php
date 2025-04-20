<?php

namespace App\Notifications;

use App\Models\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;


    public $product;
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
            'current_quantity' => $this->product->current_quantity,
            'minimum_quantity' => $this->product->minimum_quantity,
            'message' => "The stock of {$this->product->name} is low. Current quantity: {$this->product->current_quantity}, Minimum quantity: {$this->product->minimum_quantity}.",
        ];
    }
}
