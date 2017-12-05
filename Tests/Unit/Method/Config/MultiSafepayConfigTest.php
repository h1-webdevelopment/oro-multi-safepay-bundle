<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
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
        $issuerIdentifier = 'someIssueIdentifier';

        $parameterBag = new MultiSafepayConfig(
            [
                MultiSafepayConfig::ADMIN_LABEL_KEY => $adminLabel,
                MultiSafepayConfig::LABEL_KEY => $label,
                MultiSafepayConfig::SHORT_LABEL_KEY => $shortLabel,
                MultiSafepayConfig::PAYMENT_METHOD_IDENTIFIER_KEY => $paymentMethodIdentifier,
                MultiSafepayConfig::ISSUER_IDENTIFIER_KEY => $issuerIdentifier,
            ]
        );

        static::assertEquals($adminLabel, $parameterBag->getAdminLabel());
        static::assertEquals($label, $parameterBag->getLabel());
        static::assertEquals($shortLabel, $parameterBag->getShortLabel());
        static::assertEquals($paymentMethodIdentifier, $parameterBag->getPaymentMethodIdentifier());
    }
}
