<?php

namespace Tests\Unit\Services\ImportCSV;

use App\Services\ImportCSV\ImportCustomers;
use League\Csv\Reader;
use Tests\TestCase;

class ImportCustomersTest extends TestCase
{
    public function testImport()
    {
        $string = '"ID","Job Title","Email Address","FirstName LastName","registered_since","phone"
"1","Web Developer","Harvey_Thornton4640@hourpy.biz","Harvey Thornton","Saturday,April 3,2019","1-781-821-4473"';

        $this->app->make(ImportCustomers::class)
            ->import(
                Reader::createFromString($string)
                    ->setHeaderOffset(0)
            );

        $this->assertDatabaseHas('customers', [
            'first_name' => 'Harvey',
            'last_name' => 'Thornton',
            'phone' => '1-781-821-4473',
        ]);
    }
}
