<?php

declare(strict_types=1);

use DragonCode\LaravelActions\Action;

return new class extends Action
{
    /**
     * Run the actions.
     */
    public function __invoke(): void
    {
        Artisan::call('import:customers-data');
        Artisan::call('import:products-data');
    }
};
