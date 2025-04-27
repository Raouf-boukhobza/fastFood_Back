<?php

namespace App\Observers;

use App\Models\Products;
use App\Services\StockCheckService;


class ProductsQuantityObserver
{
    /**
     * Handle the Products "created" event.
     */
    public function created(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "updated" event.
     */
    public function updated(Products $products): void
    {
        if ($products->wasChanged('current_quantity')) {
            app(StockCheckService::class)->checkStock($products);
        }
    }

    /**
     * Handle the Products "deleted" event.
     */
    public function deleted(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "restored" event.
     */
    public function restored(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "force deleted" event.
     */
    public function forceDeleted(Products $products): void
    {
        //
    }
}
