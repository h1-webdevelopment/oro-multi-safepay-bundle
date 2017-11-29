<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\Config;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfig;

class MultiSafepayConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $adminLabel = 'someAdminLabel';
        $label = 'someLabel';
        $shortLabel = 'someShortLabel';
        $paymentMethodIdentifier = 'someMethodIdentifier';

        $parameterBag = new MultiSafepayConfig(
            [
                MultiSafepayConfig::ADMIN_LABEL_KEY => $adminLabel,
                MultiSafepayConfig::LABEL_KEY => $label,
                MultiSafepayConfig::SHORT_LABEL_KEY => $shortLabel,
                MultiSafepayConfig::PAYMENT_METHOD_IDENTIFIER_KEY => $paymentMethodIdentifier,
            ]
        );

        static::assertEquals($adminLabel, $parameterBag->getAdminLabel());
        static::assertEquals($label, $parameterBag->getLabel());
        static::assertEquals($shortLabel, $parameterBag->getShortLabel());
        static::assertEquals($paymentMethodIdentifier, $parameterBag->getPaymentMethodIdentifier());
    }
}
