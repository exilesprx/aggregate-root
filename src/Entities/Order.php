<?php

namespace App\Entities;

use App\Contracts\AggregateRootContract;
use App\State\OrderClosed;
use App\State\OrderCreated;
use App\State\OrderOpen;
use App\Utilities\OrderPlansCollection;
use App\Utilities\ServiceAgreementCollection;
use Ramsey\Uuid\UuidInterface;

class Order implements AggregateRootContract
{
    private $id;

    private $bundleId;

    private $orderPlans;

    private $serviceAgreements;

    private $state;

    public function __construct(UuidInterface $id, ?UuidInterface $bundleId, OrderPlansCollection $orderPlans, ServiceAgreementCollection $agreementCollection)
    {
        $this->id = $id;

        $this->orderPlans = $orderPlans;

        $this->serviceAgreements = $agreementCollection;

        $this->bundleId = $bundleId;

        $this->state = new OrderCreated();
    }

    public function addPlansToOrder(OrderPlansCollection $orderPlans)
    {
        $this->orderPlans = $this->orderPlans->merge($orderPlans->all());

        $this->transitionStateTo(new OrderOpen());
    }

    public function confirmOrder() : void
    {
        $this->orderPlans->each(
            function(OrderPlan $plan) {
                $this->addTermsFor($plan);
            }
        );

        if ($this->allServiceAgreementsSigned()) {
            $this->transitionStateTo(new OrderClosed());
        }
    }

    public function isClosed() : bool
    {
        return $this->state instanceof OrderClosed;
    }

    public function allServiceAgreementsSigned() : bool
    {
        return $this->orderPlans->reduce(
            function($carry, $plan) {
                if (is_null($carry)) {
                    $carry = true;
                }

                $hasTerms = $this->hasServiceAgreement($plan);

                return $carry && $hasTerms;
            }
        );
    }

    private function addTermsFor(OrderPlan $plan)
    {
        if ($this->hasServiceAgreement($plan)) {
            return;
        }

        $agreement = ServiceAgreement::for($plan);

        $this->serviceAgreements->add($agreement);
    }

    private function hasServiceAgreement(OrderPlan $plan) : bool
    {
        return $this->serviceAgreements->contains(
            function(ServiceAgreement $term) use($plan) {
                return (string)$term->getPlanId() === (string)$plan->getId();
            }
        );
    }

    private function transitionStateTo($state)
    {
        $this->state = $this->state->transitionTo($state);
    }
}