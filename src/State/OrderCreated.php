<?php

namespace App\State;

use Exception;

class OrderCreated
{
    public function transitionTo($state)
    {
        if (! $state instanceof OrderOpen) {
            throw new Exception(sprintf("Can only transition to %s", OrderOpen::class));
        }

        return $state;
    }
}