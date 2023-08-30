<?php

namespace Tests\Unit\Services\ImportCSV;

use App\Services\ImportCSV\ImportProducts;
use League\Csv\Reader;
use Tests\TestCase;

class ImportProductsTest extends TestCase
{
    public function testImport()
    {
        $string = '"ID","productname","price"
        "1","Merc Clothing","73.78"';

        $this->app->make(ImportProducts::class)
            ->import(
                Reader::createFromString($string)
                    ->setHeaderOffset(0)
            );

        $this->assertDatabaseHas('products', [
            'id' => 1,
            'product_name' => 'Merc Clothing',
            'price' => 73.78,
        ]);
    }
}
