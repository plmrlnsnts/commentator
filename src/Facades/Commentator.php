<?php

namespace Plmrlnsnts\Commentator\Facades;

use Illuminate\Support\Facades\Facade;

class Commentator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'commentator';
    }
}
