<?php

namespace App\Entities;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ServiceAgreement
{
    private $id;

    private $signedAt;

    private $orderPlanId;

    public function __construct(UuidInterface $id, Carbon $signedAt, UuidInterface $orderPlanId)
    {
        $this->id = $id;

        $this->signedAt = $signedAt;

        $this->orderPlanId = $orderPlanId;
    }

    public static function for(OrderPlan $orderPlan) : self
    {
        return new self(
            Uuid::uuid4(),
            Carbon::now(),
            $orderPlan->getId()
        );
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getPlanId() : UuidInterface
    {
        return $this->orderPlanId;
    }
}