<?php

namespace App\Console\Commands;

use App\Services\ImportCSV\FetchCsv;
use App\Services\ImportCSV\ImportProducts;
use Illuminate\Console\Command;

class ImportProductsData extends Command
{

    protected $signature = 'import:products-data';


    protected $description = 'Import products data';


    public function handle(
        ImportProducts $importProducts,
        FetchCsv $fetchCsv
    )
    {
        $importProducts->import(
            $fetchCsv->fetch('https://backend-developer.view.agentur-loop.com/products.csv')
        );
    }
}
