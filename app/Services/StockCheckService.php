<?php
namespace App\Services;

use App\Models\Products;
use App\Models\User;
use App\Notifications\ExpiringProductNotification;
use App\Notifications\LowStockNotification;
use Carbon\Carbon;

class StockCheckService {
    function checkStock(Products $product) {
        if ($product->current_quantity < $product->minimum_quantity) {
            $gerant = User::whereHas('employe', function ($query) {
                $query->where('role', 'gérant');
            })->first(); // Assuming 'gérant' is a role in your users table

           if($gerant){
              $gerant->notify(new LowStockNotification($product));
           }
        }
        
        if ($product->type === 'perishable' && $product->expiration_date) {
            $days = Carbon::now()->diffInDays($product->expiration_date , false);
            if ($days <= 3){
                $gerant = User::whereHas('employe', function ($query) {
                    $query->where('role', 'gérant');
                })->first(); // Assuming 'gérant' is a role in your users table

                if($gerant){
                   $gerant->notify(new ExpiringProductNotification($product));
                }
            }
        }
    }
}