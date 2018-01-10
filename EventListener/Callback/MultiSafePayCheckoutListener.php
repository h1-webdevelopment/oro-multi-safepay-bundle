<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\EventListener\Callback;

use H1\OroMultiSafepayBundle\Method\MultiSafepay;
use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Attached to the "oro_payment.callback.return" event,
 * will set a transaction to complete or failed depending on the
 * return values from MultiSafepay
 */
class MultiSafePayCheckoutListener
{
    use LoggerAwareTrait;

    /**
     * @var PaymentMethodProviderInterface
     */
    protected $paymentMethodProvider;

    /**
     * @param PaymentMethodProviderInterface $paymentMethodProvider
     */
    public function __construct(PaymentMethodProviderInterface $paymentMethodProvider)
    {
        $this->paymentMethodProvider = $paymentMethodProvider;
    }

    /**
     * @param AbstractCallbackEvent $event
     */
    public function onError(AbstractCallbackEvent $event)
    {
        $paymentTransaction = $event->getPaymentTransaction();
        if (!$paymentTransaction) {
            return;
        }
        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentTransaction->getPaymentMethod())) {
            return;
        }
        $paymentTransaction
            ->setSuccessful(false)
            ->setActive(false);
    }

    /**
     * @param AbstractCallbackEvent $event
     */
    public function onReturn(AbstractCallbackEvent $event)
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        $paymentMethodId = $paymentTransaction->getPaymentMethod();
        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentMethodId)) {
            return;
        }
        $eventData = $event->getData();

        if (!$paymentTransaction || !array_key_exists('transactionid', $eventData)) {
            return;
        }

        $responseDataFilledWithEventData = array_replace($paymentTransaction->getResponse(), $eventData);
        $paymentTransaction->setResponse($responseDataFilledWithEventData);

        try {
            $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($paymentMethodId);
            $paymentMethod->execute(MultiSafepay::COMPLETE, $paymentTransaction);
            $event->markSuccessful();
        } catch (\InvalidArgumentException $e) {
            if ($this->logger) {
                // do not expose sensitive data in context
                $this->logger->error($e->getMessage(), []);
            }
        }
    }
}