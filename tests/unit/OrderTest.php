<?php

use App\Entities\Order;
use App\Entities\OrderPlan;
use App\Utilities\OrderPlansCollection;
use App\Utilities\ServiceAgreementCollection;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class OrderTest extends Unit
{
    protected $tester;

    public function testExpectsAllTermsSigned()
    {
        $orderPlan = OrderPlansCollection::make(
            [
                new OrderPlan(
                    Uuid::uuid4(),
                    Uuid::uuid4(),
                    Uuid::uuid4()
                )
            ]
        );

        $order = new Order(
            Uuid::uuid4(),
            null,
            OrderPlansCollection::make(),
            ServiceAgreementCollection::make()
        );

        $order->addPlansToOrder($orderPlan);

        $order->confirmOrder();

        $this->assertTrue($order->allServiceAgreementsSigned());

        $this->assertTrue($order->isClosed());
    }

    public function testExpectsTermsAddedForPlansMissingTerms()
    {
        $orderPlan = OrderPlansCollection::make(
            [
                new OrderPlan(
                    Uuid::uuid4(),
                    Uuid::uuid4(),
                    Uuid::uuid4()
                )
            ]
        );

        $order = new Order(
            Uuid::uuid4(),
            null,
            OrderPlansCollection::make(
                [
                    new OrderPlan(
                        Uuid::uuid4(),
                        Uuid::uuid4(),
                        Uuid::uuid4()
                    )
                ]
            ),
            ServiceAgreementCollection::make()
        );

        $order->addPlansToOrder($orderPlan);

        $order->confirmOrder();

        $this->assertTrue($order->allServiceAgreementsSigned());

        $this->assertTrue($order->isClosed());
    }
}