<?php
namespace App\Services;

use App\Models\Products;
use App\Models\User;
use App\Notifications\LowStockNotification;


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

    }
}