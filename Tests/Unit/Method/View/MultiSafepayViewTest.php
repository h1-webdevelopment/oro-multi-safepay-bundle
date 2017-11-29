<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\View;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfig;
use H1\OroMultiSafepayBundle\Method\View\MultiSafepayView;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;

class MultiSafepayViewTest extends \PHPUnit_Framework_TestCase
{
    /** @var MultiSafepayView */
    protected $methodView;

    /** @var MultiSafepayConfig|\PHPUnit_Framework_MockObject_MockObject */
    protected $config;

    protected function setUp()
    {
        $this->config = $this->createMock(MultiSafepayConfig::class);

        $this->methodView = new MultiSafepayView($this->config);
    }

    /*public function testGetOptions()
    {
        $data = ['pay_to' => 'Pay To', 'send_to' => 'Send To'];

        $this->config->expects(static::once())
            ->method('getPayTo')
            ->willReturn($data['pay_to']);
        $this->config->expects(static::once())
            ->method('getSendTo')
            ->willReturn($data['send_to']);

        // @var PaymentContextInterface|\PHPUnit_Framework_MockObject_MockObject $context
        $context = $this->createMock(PaymentContextInterface::class);

        $this->assertEquals($data, $this->methodView->getOptions($context));
    }*/

    public function testGetBlock()
    {
        $this->assertEquals('_payment_methods_multi_safepay_widget', $this->methodView->getBlock());
    }

    public function testGetLabel()
    {
        $label = 'label';

        $this->config->expects(static::once())
            ->method('getLabel')
            ->willReturn($label);

        $this->assertEquals($label, $this->methodView->getLabel());
    }

    public function testShortGetLabel()
    {
        $label = 'label';

        $this->config->expects(static::once())
            ->method('getShortLabel')
            ->willReturn($label);

        $this->assertEquals($label, $this->methodView->getShortLabel());
    }

    public function testGetAdminLabel()
    {
        $label = 'label';

        $this->config->expects(static::once())
            ->method('getAdminLabel')
            ->willReturn($label);

        $this->assertEquals($label, $this->methodView->getAdminLabel());
    }

    public function testGetPaymentMethodIdentifier()
    {
        $identifier = 'id';

        $this->config->expects(static::once())
            ->method('getPaymentMethodIdentifier')
            ->willReturn($identifier);

        $this->assertEquals($identifier, $this->methodView->getPaymentMethodIdentifier());
    }
}
