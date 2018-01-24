<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\Factory;

use H1\OroMultiSafepayBundle\Manager\MultiSafepayManager;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\Factory\MultiSafepayMethodFactory;
use H1\OroMultiSafepayBundle\Method\Factory\MultiSafepayMethodFactoryInterface;
use H1\OroMultiSafepayBundle\Method\MultiSafepay;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Symfony\Component\Routing\RouterInterface;

class MultiSafepayPaymentMethodFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MultiSafepayMethodFactoryInterface
     */
    private $factory;

    protected function setUp()
    {
        $manager = $this->createMock(MultiSafepayManager::class);
        $router = $this->createMock(RouterInterface::class);
        $doctrineHelper = $this->createMock(DoctrineHelper::class);

        $this->factory = new MultiSafepayMethodFactory($manager, $router, $doctrineHelper);
    }

    public function testCreate()
    {
        /** @var MultiSafepayConfigInterface|\PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->createMock(MultiSafepayConfigInterface::class);
        $manager = $this->createMock(MultiSafepayManager::class);
        $router = $this->createMock(RouterInterface::class);
        $doctrineHelper = $this->createMock(DoctrineHelper::class);

        $method = new MultiSafepay($config, $manager, $router, $doctrineHelper);

        static::assertEquals($method, $this->factory->create($config));
    }
}
