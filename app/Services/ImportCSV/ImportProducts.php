<?php

namespace App\Services\ImportCSV;

use App\Entities\ResultEntity;
use App\Models\Product;
use League\Csv\Reader;

class ImportProducts implements ImportDataInterface
{
    public function __construct(private readonly ResultEntity $resultEntity)
    {
    }

    public function import(Reader $csv): ResultEntity
    {

        Product::truncate();

        foreach ($csv->getRecords() as $record) {
            try {

                Product::create([
                    'id' => $record['ID'],
                    'product_name' => $record['productname'],
                    'price' => $record['price'],
                ]);
            } catch (\Exception $exception) {
                $this->resultEntity->incrementFailed();
            }

            $this->resultEntity->incrementSuccessful();
        }

        return $this->resultEntity;
    }
}
