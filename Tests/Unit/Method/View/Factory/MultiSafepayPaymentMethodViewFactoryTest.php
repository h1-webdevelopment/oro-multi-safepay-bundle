<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\View\Factory;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\View\Factory\MultiSafepayViewFactory;
use H1\OroMultiSafepayBundle\Method\View\Factory\MultiSafepayViewFactoryInterface;
use H1\OroMultiSafepayBundle\Method\View\MultiSafepayView;
use Symfony\Component\Form\FormFactoryInterface;

class MultiSafepayPaymentMethodViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MultiSafepayViewFactoryInterface
     */
    private $factory;

    protected function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);

        $this->factory = new MultiSafepayViewFactory($this->formFactory);
    }

    public function testCreate()
    {
        /** @var MultiSafepayConfigInterface|\PHPUnit_Framework_MockObject_MockObject $config */
        $config = $this->createMock(MultiSafepayConfigInterface::class);

        $method = new MultiSafepayView($config, $this->formFactory);

        static::assertEquals($method, $this->factory->create($config));
    }
}
