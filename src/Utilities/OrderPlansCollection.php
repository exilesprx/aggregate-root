<?php

namespace App\Utilities;

use App\Entities\OrderPlan;
use Exception;
use Illuminate\Support\Collection;

class OrderPlansCollection extends Collection
{
    private static $allowedType = OrderPlan::class;

    public function add($item)
    {
        $this->isAllowed($item);

        return parent::add($item);
    }

    public function push(...$values)
    {
        foreach ($values as $value) {
            $this->isAllowed($value);
        }

        return parent::push($values);
    }

    private function isAllowed($item)
    {
        if (!$item instanceof self::$allowedType) {
            throw new Exception(sprintf("Only types %s are allowed.", self::$allowedType));
        }
    }
}