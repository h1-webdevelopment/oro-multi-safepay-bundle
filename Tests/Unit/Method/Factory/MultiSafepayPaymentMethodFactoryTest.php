<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\Factory;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\Factory\MultiSafepayMethodFactory;
use H1\OroMultiSafepayBundle\Method\Factory\MultiSafepayMethodFactoryInterface;
use H1\OroMultiSafepayBundle\Method\MultiSafepay;

class MultiSafepayPaymentMethodFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MultiSafepayMethodFactoryInterface
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new MultiSafepayMethodFactory();
    }

    public function testCreate()
    {
        /** @var MultiSafepayConfigInterface|\PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->createMock(MultiSafepayConfigInterface::class);

        $method = new MultiSafepay($config);

        static::assertEquals($method, $this->factory->create($config));
    }
}
