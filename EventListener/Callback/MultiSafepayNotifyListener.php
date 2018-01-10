<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\EventListener\Callback;

use Monolog\Logger;
use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class MultiSafepayNotifyListener
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var PaymentMethodProviderInterface
     */
    private $paymentMethodProvider;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * MultiSafepayNotifyListener constructor.
     *
     * @param Session $session
     * @param PaymentMethodProviderInterface $paymentMethodProvider
     */
    public function __construct(Session $session, PaymentMethodProviderInterface $paymentMethodProvider)
    {
        $this->session = $session;
        $this->paymentMethodProvider = $paymentMethodProvider;
    }
    /**
     * @param AbstractCallbackEvent $event
     */
    public function onNotify(AbstractCallbackEvent $event)
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentTransaction->getPaymentMethod())) {
            $this->logger->addWarning(
                sprintf(
                    'Payment Method Provider [%s] does not have payment method [%s]',
                    \get_class_methods($this->paymentMethodProvider),
                    $paymentTransaction->getPaymentMethod()
                )
            );
            return;
        }

        $event->markSuccessful();
        $this->logger->addInfo('Payment successfully marked completed');

//        print_r($event);
////        dump($paymentTransaction); exit;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
}