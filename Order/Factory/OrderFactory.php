<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\Order\Factory;

use H1\OroMultiSafepayBundle\Order\Decorator\CustomerDataDecorator;
use H1\OroMultiSafepayBundle\Order\Decorator\OrderDataDecoratorInterface;
use H1\OroMultiSafepayBundle\Order\Order;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

/**
 * Generate an order based on the given PaymentTransaction.
 * This class will use the given decorators to add values to the final order.
 */
class OrderFactory
{
    /**
     * @var OrderDataDecoratorInterface[]
     */
    private $decorators = [];

    public function __construct()
    {
        $this->decorators = [
            new CustomerDataDecorator()
        ];
    }


    /**
     * @param PaymentTransaction $paymentTransaction
     * @return Order
     */
    public function create(PaymentTransaction $paymentTransaction) {
        $order = new Order();

        /** @var OrderDataDecoratorInterface $decorator */
        foreach ($this->decorators as $decorator) {
            $decorator
                ->setPaymentTransaction($paymentTransaction)
                ->decorate($order);
        }

        return $order;
    }
}