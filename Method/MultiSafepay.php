<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle\Method;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;

/**
 * Class MultiSafepay
 */
class MultiSafepay implements PaymentMethodInterface
{
    /**
     * @var MultiSafepayConfigInterface
     */
    protected $config;

    /**
     * @param MultiSafepayConfigInterface $config
     */
    public function __construct(MultiSafepayConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->setAction(PaymentMethodInterface::PURCHASE);
        $paymentTransaction->setActive(true);
        $paymentTransaction->setSuccessful(true);


        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->config->getPaymentMethodIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PaymentContextInterface $context)
    {
        return true;
    }

    /**
     * This is where you define which transaction types are associated with the payment method. To keep it simple, for Collect On Delivery a single transaction is defined. Thus, it will work the following way: when a user submits an order, the “purchase” transaction takes place, and the order status becomes "purchased".
     * Check https://github.com/oroinc/orocommerce/blob/master/src/Oro/Bundle/PaymentBundle/Method/PaymentMethodInterface.php for more information on other predefined transactions.
     *
     * {@inheritdoc}
     */
    public function supports($actionName)
    {
        return $actionName === self::PURCHASE;
    }
}