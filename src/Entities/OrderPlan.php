<?php

namespace App\Entities;

use Ramsey\Uuid\UuidInterface;

class OrderPlan
{
    private $id;

    private $planId;

    private $orderId;

    public function __construct(UuidInterface $id, UuidInterface $planId, UuidInterface $orderId)
    {
        $this->id = $id;

        $this->planId = $planId;

        $this->orderId = $orderId;
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }
}