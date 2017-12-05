<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Provider;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\Config\Provider\MultiSafepayConfigProviderInterface;
use H1\OroMultiSafepayBundle\Method\Factory\MultiSafepayMethodFactoryInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;

/**
 * Class MultiSafepayMethodProvider
 */
class MultiSafepayMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var MultiSafepayMethodFactoryInterface
     */
    protected $factory;

    /**
     * @var MultiSafepayConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param MultiSafepayConfigProviderInterface $configProvider
     * @param MultiSafepayMethodFactoryInterface  $factory
     */
    public function __construct(
        MultiSafepayConfigProviderInterface $configProvider,
        MultiSafepayMethodFactoryInterface $factory
    ) {
        parent::__construct();

        $this->configProvider = $configProvider;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function collectMethods()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addMultiSafepayMethod($config);
        }
    }

    /**
     * @param MultiSafepayConfigInterface $config
     */
    protected function addMultiSafepayMethod(MultiSafepayConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
