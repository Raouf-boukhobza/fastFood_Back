<?php

namespace App\Console\Commands;

use App\Models\Products;
use App\Services\StockCheckService;
use Illuminate\Console\Command;

class CheckStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all products for low stock or expiration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new StockCheckService();

        $products = Products::all();
        foreach ($products as $product) {
            $service->checkStock($product);
        }
        $this->info('Stock check completed.');
    }
}
