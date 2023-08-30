<?php
namespace App\Services\ImportCSV;
use App\Models\Product;
use League\Csv\Reader;

class ImportProducts implements ImportDataInterface
{

    public function import(Reader $csv)
    {
        Product::truncate();

        foreach ($csv->getRecords() as $record){
            Product::create([
                'id' => $record['ID'],
                'product_name' => $record['productname'],
                'price' => $record['price']
            ]);
        }

    }
}
