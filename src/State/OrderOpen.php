<?php

namespace App\State;

use Exception;

class OrderOpen
{
    public function transitionTo($state)
    {
        if (! $state instanceof OrderClosed) {
            throw new Exception(sprintf("Can only transition to %s", OrderClosed::class));
        }

        return $state;
    }
}