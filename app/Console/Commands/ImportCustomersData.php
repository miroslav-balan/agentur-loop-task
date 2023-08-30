<?php

namespace App\Console\Commands;

use App\Services\ImportCSV\FetchCsv;
use App\Services\ImportCSV\ImportCustomers;
use App\Services\ImportCSV\ImportProducts;
use Illuminate\Console\Command;

class ImportCustomersData extends Command
{

    protected $signature = 'import:customers-data';


    protected $description = 'Import customer data';


    public function handle(
        ImportCustomers $importCustomers,
        FetchCsv $fetchCsv
    )
    {
        $importCustomers->import(
            $fetchCsv->fetch('https://backend-developer.view.agentur-loop.com/customers.csv')
        );
    }
}
