<?php

namespace App\Console\Commands;

use App\Services\ImportCSV\FetchCsv;
use App\Services\ImportCSV\ImportCustomers;
use Illuminate\Console\Command;

class ImportCustomersData extends Command
{
    protected $signature = 'import:customers-data';

    protected $description = 'Import customer data';

    public function handle(
        ImportCustomers $importCustomers,
        FetchCsv $fetchCsv
    ) {
        $result = $importCustomers->import(
            $fetchCsv->fetch('https://backend-developer.view.agentur-loop.com/customers.csv')
        );

        $this->info(
            $result->getSuccessful().' customers has been imported successfully | '.
            $result->getFailed().' customers failed to import',
        );
    }
}
