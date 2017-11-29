<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Method;

use H1\OroMultiSafepayBundle\DependencyInjection\H1OroMultiSafepayExtension;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\MultiSafepay;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Tests\Unit\Method\ConfigTestTrait;

class MultiSafepayTest extends \PHPUnit_Framework_TestCase
{
    use ConfigTestTrait;

    /** @var MultiSafepay */
    protected $method;

    /** @var MultiSafepayConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $config;

    protected function setUp()
    {
        $this->config = $this->createMock(MultiSafepayConfigInterface::class);

        $this->method = new MultiSafepay($this->config);
    }

    public function testExecute()
    {
        $transaction = new PaymentTransaction();
        $this->assertFalse($transaction->isSuccessful());

        $this->assertEquals([], $this->method->execute('', $transaction));
        $this->assertTrue($transaction->isSuccessful());
    }

    public function testGetIdentifier()
    {
        $identifier = 'id';

        $this->config->expects(static::once())
            ->method('getPaymentMethodIdentifier')
            ->willReturn($identifier);

        $this->assertEquals($identifier, $this->method->getIdentifier());
    }

    /**
     * @param bool $expected
     * @param string $actionName
     *
     * @dataProvider supportsDataProvider
     */
    public function testSupports($expected, $actionName)
    {
        $this->assertEquals($expected, $this->method->supports($actionName));
    }

    /**
     * @return array
     */
    public function supportsDataProvider()
    {
        return [
            [false, MultiSafepay::AUTHORIZE],
            [false, MultiSafepay::CAPTURE],
            [false, MultiSafepay::CHARGE],
            [false, MultiSafepay::VALIDATE],
            [true, MultiSafepay::PURCHASE],
        ];
    }

    public function testIsApplicable()
    {
        /** @var PaymentContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->createMock(PaymentContextInterface::class);
        $this->assertTrue($this->method->isApplicable($context));
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensionAlias()
    {
        return H1OroMultiSafepayExtension::ALIAS;
    }
}
