<?php

namespace App\Services\ImportCSV;

use League\Csv\Reader;

class FetchCsv
{
    public function fetch(string $url): Reader
    {
        $context = stream_context_create([
            'http' => [
                'header' => 'Authorization: Basic '.base64_encode('loop:backend_dev'),
            ],
        ]);

        $csv = Reader::createFromString(
            file_get_contents($url, false, $context)
        );
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
