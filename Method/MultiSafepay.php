<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle\Method;

use Oro\Bundle\CustomerBundle\Entity\Customer;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use H1\OroMultiSafepayBundle\Manager\MultiSafepayManager;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use LogicException;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MultiSafepay
 */
class MultiSafepay implements PaymentMethodInterface
{
    /**
     * Payment has been successfully completed.
     *
     * This event is called by the EventListener\Callback\MultiSafePayCheckoutListener
     * as soon as the payment has been processed successfully
     */
    const COMPLETE = 'complete';

    /**
     * A payment link has been generated, but no payment has been received yet.
     */
    const INITIALIZED = 'initialized';

    /**
     * Rejected by the credit card company.
     */
    const DECLINED = 'declined';

    /**
     * Canceled by the merchant (only applies to the status Initialised or Uncleared).
     */
    const CANCELED = 'canceled';

    /**
     * Depending on the payment method unfinished transactions automatically expire after a predefined period.
     */
    const EXPIRED = 'expired';

    /**
     * Waiting for manual permission of the merchant to approve/disapprove the payment.
     */
    const UNCLEARED = 'uncleared';

    /**
     * Payment has been refunded to the customer.
     */
    const REFUNDED = 'refunded';

    /**
     * The payment has been partially refunded to the customer.
     */
    const PARTIAL_REFUNDED = 'partial_refunded';

    /**
     * Payout/refund has been temporary put on reserved, a temporary status, till the e-wallet has
     */
    const RESERVED = 'reserved';

    /**
     * Failed payment.
     */
    const VOID = 'void';

    /**
     * Forced reversal of funds initiated by consumer’s issuing bank. Only applicable to direct debit and credit card payments.
     */
    const CHARGEDBACK = 'chargedback';

    /**
     * @var MultiSafepayConfigInterface
     */
    protected $config;

    /**
     * @var MultiSafepayManager
     */
    private $multiSafepayManager;

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var DoctrineHelper
     */
    private $doctrineHelper;

    /**
     * @param MultiSafepayConfigInterface $config
     * @param MultiSafepayManager $multiSafepayManager
     * @param RouterInterface $router
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(
        MultiSafepayConfigInterface $config,
        MultiSafepayManager $multiSafepayManager,
        RouterInterface $router,
        DoctrineHelper $doctrineHelper
    )
    {
        $this->config = $config;
        $this->multiSafepayManager = $multiSafepayManager;
        $this->router = $router;
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction): array
    {
        return $this->{$action}($paymentTransaction) ?: [];
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return array
     */
    public function purchase(PaymentTransaction $paymentTransaction): array
    {
        $additionalData = json_decode($paymentTransaction->getTransactionOptions()['additionalData'], true);

        $urlEndpoints = $this->getUrlEndPoints($paymentTransaction);

        $order = [
            'type' => $this->getPaymentType($paymentTransaction, $additionalData),
            'order_id' => $paymentTransaction->getAccessIdentifier(),
            'currency' => $paymentTransaction->getCurrency(),
            'amount' => $paymentTransaction->getAmount() * 100,
            'gateway' => $additionalData['gateway'],
            'description' => 'Payment',
            'payment_options' => [
                'notification_url' => $urlEndpoints['notifyUrl'],
                'redirect_url' => $urlEndpoints['returnUrl'],
                'cancel_url' => $urlEndpoints['cancelUrl'],
                'close_window' => 'true',
            ],
        ];

        $entity = $this->doctrineHelper->getEntity(
            $paymentTransaction->getEntityClass(),
            $paymentTransaction->getEntityIdentifier()
        );

        if ($entity instanceof Order) {
            $description = 'Order';
            $customer = $entity->getCustomer();
            if ($customer instanceof Customer) {
                $description = $customer->getName();
            }
            $description .= ' - ' . $entity->getIdentifier();
            $order['description'] = $description;
        }

        if (array_key_exists('msp_issuer', $additionalData)) {
            $order['gateway_info'] = [
                'issuer_id' => $additionalData['msp_issuer'],
            ];
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $res = $this->multiSafepayManager->configureByConfig($this->config)
            ->getClient()
            ->postOrders($order);

        return [
            // @codingStandardsIgnoreLine Zend.NamingConventions.ValidVariableName.NotCamelCaps
            'purchaseRedirectUrl' => $res->payment_url,
        ];
    }

    /**
     * This event is called by the EventListener\Callback\MultiSafePayCheckoutListener
     * as soon as the payment has been processed successfully
     * @param PaymentTransaction $paymentTransaction
     * @throws LogicException
     */
    public function complete(PaymentTransaction $paymentTransaction)
    {
        $transactionid =$paymentTransaction->getResponse()['transactionid'];

        $response = $this->multiSafepayManager
            ->configureByConfig($this->config)
            ->getClient()
            ->getOrder($transactionid);

        if ($response->status === 'completed') {
            $paymentTransaction
                ->setSuccessful(true)
                ->setActive(false)
                ->setResponse((array)$response)
                ->setReference($transactionid);
        }
    }

    /**
     * @param PaymentTransaction $paymentTransaction
     * @return array
     * @throws LogicException
     */
    public function updateOrderStatus(PaymentTransaction $paymentTransaction)
    {
        $response = $this->multiSafepayManager
            ->configureByConfig($this->config)
            ->getClient()
            ->getOrder($paymentTransaction->getAccessIdentifier());

        if ($response->status === 'completed') {
            $paymentTransaction
                ->setSuccessful(true)
                ->setActive(false)
                ->setResponse((array)$response);
//        } else {
//            #todo what if an order is payed and later an order is declined or something. Do we change the status of the payment again?
//            $paymentTransaction
//                ->setSuccessful(true)
//                ->setActive(false)
//                ->setResponse((array)$response);
        }


        return (array)$response;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->config->getPaymentMethodIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PaymentContextInterface $context): bool
    {
        return true;
    }

    /**
     * This is where you define which transaction types are associated with the payment method. To keep it simple, for Collect On Delivery a single transaction is defined. Thus, it will work the following way: when a user submits an order, the “purchase” transaction takes place, and the order status becomes "purchased".
     * Check https://github.com/oroinc/orocommerce/blob/master/src/Oro/Bundle/PaymentBundle/Method/PaymentMethodInterface.php for more information on other predefined transactions.
     *
     * {@inheritdoc}
     */
    public function supports($actionName): bool
    {
        return \in_array(
            $actionName,
            [self::PURCHASE, self::COMPLETE],
            true
        );
    }


    /**
     * Generates cancel/return and notify URLs.
     *
     * @param PaymentTransaction $paymentTransaction
     *
     * @return array
     */
    protected function getUrlEndPoints(PaymentTransaction $paymentTransaction): array
    {

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return [
            'cancelUrl' => $this->router->generate(
                'oro_payment_callback_error',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'returnUrl' => $this->router->generate(
                'oro_payment_callback_return',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),

            'notifyUrl' => $this->router->generate(
                'h1_oro_multi_savepay_callback_notify',
                [
                    'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                    'accessToken' => $paymentTransaction->getAccessToken()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];
    }

    /**
     * Return either redirect or direct as payment type
     *
     * @See: https://www.multisafepay.com/documentation/doc/API-Reference/#API-Reference-Createanorder
     *
     * @param PaymentTransaction $paymentTransaction
     * @param array $additionalData
     *
     * @return string
     */
    private function getPaymentType(PaymentTransaction $paymentTransaction, array $additionalData): string
    {
        if (!array_key_exists('msp_issuer', $additionalData)) {
            return 'redirect';
        }

        return 'direct';
    }
}
