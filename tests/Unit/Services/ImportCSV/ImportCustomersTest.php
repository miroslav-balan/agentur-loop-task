<?php
namespace Tests\Unit\Services\ImportCSV;
use App\Services\ImportCSV\ImportCustomers;
use Tests\TestCase;

class ImportCustomersTest extends TestCase
{

    public function testImport()
    {
        $this->app->make(ImportCustomers::class)->import();
    }

}
