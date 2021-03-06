<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\Provider;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\Config\Provider\MultiSafepayConfigProviderInterface;
use H1\OroMultiSafepayBundle\Method\Factory\MultiSafepayMethodFactoryInterface;
use H1\OroMultiSafepayBundle\Method\Provider\MultiSafepayMethodProvider;
use Oro\Bundle\PaymentBundle\Tests\Unit\Method\Provider\AbstractMethodProviderTest;

class MultiSafepayMethodProviderTest extends AbstractMethodProviderTest
{
    protected function setUp()
    {
        $this->factory = $this->createMock(MultiSafepayMethodFactoryInterface::class);
        $this->configProvider = $this->createMock(MultiSafepayConfigProviderInterface::class);
        $this->paymentConfigClass = MultiSafepayConfigInterface::class;
        $this->methodProvider = new MultiSafepayMethodProvider($this->configProvider, $this->factory);
    }
}
