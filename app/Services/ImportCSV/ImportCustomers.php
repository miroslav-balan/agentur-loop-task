<?php
namespace App\Services\ImportCSV;
use App\Models\Customer;
use League\Csv\Reader;

class ImportCustomers
{
    public function __construct(private readonly fetchCsv $fetchCsv)
    {
    }

    public function import()
    {
        Customer::truncate();

        $csv = $this->fetchCsv->fetch('https://backend-developer.view.agentur-loop.com/customers.csv');
        foreach ($csv->getRecords() as $record){
            $name = explode(' ', $record['FirstName LastName']);
            Customer::create([
                'id' => $record['ID'],
                'job_title' => $record['Job Title'],
                'email' => $record['Email Address'],
                'first_name' => $name[0] ?? '',
                'last_name' => $name[1] ?? '',
                'phone' => $record['phone'],
                'created_at' => $record['registered_since']
            ]);
        }

    }

}
