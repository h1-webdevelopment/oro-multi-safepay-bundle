<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\View\Provider;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\Config\Provider\MultiSafepayConfigProviderInterface;
use H1\OroMultiSafepayBundle\Method\View\Factory\MultiSafepayViewFactoryInterface;
use H1\OroMultiSafepayBundle\Method\View\Provider\MultiSafepayViewProvider;
use Oro\Bundle\PaymentBundle\Tests\Unit\Method\View\Provider\AbstractMethodViewProviderTest;

class MultiSafepayMethodViewProviderTest extends AbstractMethodViewProviderTest
{
    public function setUp()
    {
        $this->factory = $this->createMock(MultiSafepayViewFactoryInterface::class);
        $this->configProvider = $this->createMock(MultiSafepayConfigProviderInterface::class);
        $this->paymentConfigClass = MultiSafepayConfigInterface::class;
        $this->provider = new MultiSafepayViewProvider($this->configProvider, $this->factory);
    }
}
