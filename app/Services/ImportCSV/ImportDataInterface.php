<?php

namespace App\Services\ImportCSV;

use League\Csv\Reader;

interface ImportDataInterface
{
    public function import(Reader $csv);
}
