<?php

namespace App\Entities;

class ResultEntity
{
    private int $failed = 0;

    private int $successful = 0;

    public function incrementFailed(): void
    {
        $this->failed += 1;
    }

    public function incrementSuccessful(): void
    {
        $this->successful += 1;
    }

    public function getFailed(): int
    {
        return $this->failed;
    }

    public function getSuccessful(): int
    {
        return $this->successful;
    }
}
