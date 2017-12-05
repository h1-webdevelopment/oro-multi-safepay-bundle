<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle\Method;

use H1\OroMultiSafepayBundle\Manager\MultiSafepayManager;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
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
    const COMPLETE = 'complete';

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
     * @param MultiSafepayConfigInterface $config
     * @param MultiSafepayManager         $multiSafepayManager
     * @param RouterInterface             $router
     */
    public function __construct(MultiSafepayConfigInterface $config, MultiSafepayManager $multiSafepayManager, RouterInterface $router)
    {
        $this->config = $config;
        $this->multiSafepayManager = $multiSafepayManager;
        $this->router = $router;
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
    public function purchase(PaymentTransaction $paymentTransaction)
    {
        $additionalData = json_decode($paymentTransaction->getTransactionOptions()['additionalData'], true);

        $cancelUrl = $this->router->generate(
            'oro_payment_callback_error',
            [
                'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $returnURL = $this->router->generate(
            'oro_payment_callback_return',
            [
                'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );


        $order = [
            'type' => 'direct',
            'order_id' => $paymentTransaction->getAccessIdentifier(), //TODO: this is incorrect
            'currency' => 'EUR',
            'amount' => 1000,
            'description' => 'Demo Transaction',
            'items' => 'items list',
            'manual' => 'false',
            'gateway' => 'IDEAL',
            'days_active' => '30',
            'payment_options' => [
                'notification_url' => BASE_URL.'notificationController.php?type=initial',
                'redirect_url' => $returnURL,
                'cancel_url' => $cancelUrl,
                'close_window' => 'true',
            ],
            'customer' => [
                'locale' => 'nl_NL',
                'ip_address' => '127.0.0.1',
                'forwarded_ip' => '127.0.0.1',
                'first_name' => 'Jan',
                'last_name' => 'Modaal',
                'address1' => 'Kraanspoor',
                'address2' => '',
                'house_number' => '39',
                'zip_code' => '1032 SC',
                'city' => 'Amsterdam',
                'state' => '',
                'country' => 'NL',
                'phone' => '0208500500',
                'email' => 'test@test.nl',
            ],
            'gateway_info' => [
                'issuer_id' => $additionalData['msp_issuer'],
            ],
            'plugin' => [
                'shop' => 'MultiSafepay Toolkit',
                'shop_version' => TOOLKIT_VERSION,
                'plugin_version' => TOOLKIT_VERSION,
                'partner' => 'MultiSafepay',
                'shop_root_url' => 'http://www.demo.nl',
            ],
        ];

        $res = $this->multiSafepayManager->configureByConfig($this->config)
        ->getClient()
        ->postOrders($order);

        return [
            // @codingStandardsIgnoreLine Zend.NamingConventions.ValidVariableName.NotCamelCaps
            'purchaseRedirectUrl' => $res->payment_url,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function complete(PaymentTransaction $paymentTransaction)
    {
        dump($paymentTransaction);
        exit;
    }

    /**
     * {@inheritdoc}
     */
    public function capture()
    {
        dump("capturee");
        exit;
    }

    /**
     * {@inheritdoc}
     */
    public function charge()
    {
        dump("charge");
        exit;
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
        return \in_array(
            $actionName,
            [self::PURCHASE, self::CHARGE, self::CAPTURE, self::COMPLETE],
            true
        );
    }
}
