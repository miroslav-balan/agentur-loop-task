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
        $result = $importProducts->import(
            $fetchCsv->fetch('https://backend-developer.view.agentur-loop.com/products.csv')
        );

        $this->info(
            $result->getSuccessful() . ' products has been imported successfully | ' .
            $result->getFailed() . ' products failed to import',
        );
    }
}
