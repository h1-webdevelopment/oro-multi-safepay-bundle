<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\Order\Decorator;
use H1\OroMultiSafepayBundle\Order\Order;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

/**
 * Class AbstractOrderDataDecorator
 */
abstract class AbstractOrderDataDecorator implements OrderDataDecoratorInterface
{
    /**
     * @var PaymentTransaction
     */
    protected $paymentTransaction;

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return OrderDataDecoratorInterface
     */
    public function setPaymentTransaction(PaymentTransaction $paymentTransaction): OrderDataDecoratorInterface
    {
        $this->paymentTransaction = $paymentTransaction;

        return $this;
    }

    /**
     * Decorate order data with your own custom fields and values.
     *
     * @param $order
     */
    abstract public function decorate(Order $order);
}