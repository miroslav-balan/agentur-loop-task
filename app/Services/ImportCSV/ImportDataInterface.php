<?php

namespace App\Services\ImportCSV;

use App\Entities\ResultEntity;
use League\Csv\Reader;

interface ImportDataInterface
{
    public function import(Reader $csv): ResultEntity;
}
