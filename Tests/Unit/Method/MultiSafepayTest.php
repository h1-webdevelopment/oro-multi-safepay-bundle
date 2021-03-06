<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Method;

use H1\OroMultiSafepayBundle\Bridge\MultiSafepayApiClientBridgeInterface;
use H1\OroMultiSafepayBundle\DependencyInjection\H1OroMultiSafepayExtension;
use H1\OroMultiSafepayBundle\Manager\MultiSafepayManager;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\MultiSafepay;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Tests\Unit\Method\ConfigTestTrait;
use Symfony\Component\Routing\RouterInterface;

class MultiSafepayTest extends \PHPUnit_Framework_TestCase
{
    use ConfigTestTrait;

    /**
     * @var MultiSafepay
     */
    protected $method;

    /**
     * @var MultiSafepayConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $config;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var DoctrineHelper
     */
    private $doctrineHelper;
    /**
     * @var MultiSafepayManager
     */
    private $manager;

    protected function setUp()
    {
        $this->config = $this->createMock(MultiSafepayConfigInterface::class);
        $this->manager = $this->createMock(MultiSafepayManager::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->doctrineHelper = $this->createMock(DoctrineHelper::class);

        $this->method = new MultiSafepay($this->config, $this->manager, $this->router, $this->doctrineHelper);
    }

    public function testExecute()
    {
        $client = $this->createMock(MultiSafepayApiClientBridgeInterface::class);

        $client->expects(static::once())
            ->method('getOrder')
            ->willReturn((object) ['status' => 'completed']);

        $this->manager->expects(static::once())
            ->method('configureByConfig')
            ->willReturn($this->manager);

        $this->manager->expects(static::once())
            ->method('getClient')
            ->willReturn($client);


        $transaction = new PaymentTransaction();
        $transaction->setResponse(['transactionid' => '1234']);
        $this->assertFalse($transaction->isSuccessful());

        $this->assertEquals([], $this->method->execute('complete', $transaction));
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
