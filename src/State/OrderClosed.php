<?php

namespace App\State;

use Exception;

class OrderClosed
{
    public function transitionTo($state)
    {
        throw new Exception("Cannot transition to another state.");
    }
}