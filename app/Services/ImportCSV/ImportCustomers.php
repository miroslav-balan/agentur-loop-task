<?php

namespace App\Services\ImportCSV;

use App\Entities\ResultEntity;
use App\Models\Customer;
use League\Csv\Reader;

class ImportCustomers implements ImportDataInterface
{
    public function __construct(private readonly ResultEntity $resultEntity)
    {
    }

    public function import(Reader $csv): ResultEntity
    {

        Customer::truncate();

        foreach ($csv->getRecords() as $record) {
            $name = explode(' ', $record['FirstName LastName']);
            try {
                Customer::create([
                    'id' => $record['ID'],
                    'job_title' => $record['Job Title'],
                    'email' => $record['Email Address'],
                    'first_name' => $name[0] ?? '',
                    'last_name' => $name[1] ?? '',
                    'phone' => $record['phone'],
                    'created_at' => $record['registered_since'],
                ]);
            } catch (\Exception $exception) {
                $this->resultEntity->incrementFailed();
            }

            $this->resultEntity->incrementSuccessful();
        }

        return $this->resultEntity;
    }
}
