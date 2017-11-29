<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\View\Factory;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\View\Factory\MultiSafepayViewFactory;
use H1\OroMultiSafepayBundle\Method\View\Factory\MultiSafepayViewFactoryInterface;
use H1\OroMultiSafepayBundle\Method\View\MultiSafepayView;

class MultiSafepayPaymentMethodViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MultiSafepayViewFactoryInterface
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new MultiSafepayViewFactory();
    }

    public function testCreate()
    {
        /** @var MultiSafepayConfigInterface|\PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->createMock(MultiSafepayConfigInterface::class);

        $method = new MultiSafepayView($config);

        static::assertEquals($method, $this->factory->create($config));
    }
}
