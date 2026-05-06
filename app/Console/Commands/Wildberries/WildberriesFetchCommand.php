<?php

namespace App\Console\Commands\Wildberries;

use App\Console\Commands\BaseFetchCommand;

abstract class WildberriesFetchCommand extends BaseFetchCommand
{
    protected function getServiceName(): string
    {
        return 'wildberries';
    }
}
