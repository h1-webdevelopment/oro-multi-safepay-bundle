<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\View\Provider;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\Config\Provider\MultiSafepayConfigProviderInterface;
use H1\OroMultiSafepayBundle\Method\View\Factory\MultiSafepayViewFactoryInterface;
use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;

/**
 * Class MultiSafepayViewProvider
 */
class MultiSafepayViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var MultiSafepayViewFactoryInterface */
    private $factory;

    /** @var MultiSafepayConfigProviderInterface */
    private $configProvider;

    /**
     * @param MultiSafepayConfigProviderInterface $configProvider
     * @param MultiSafepayViewFactoryInterface    $factory
     */
    public function __construct(
        MultiSafepayConfigProviderInterface $configProvider,
        MultiSafepayViewFactoryInterface $factory
    ) {
        $this->factory = $factory;
        $this->configProvider = $configProvider;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildViews()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addMultiSafepayView($config);
        }
    }

    /**
     * @param MultiSafepayConfigInterface $config
     */
    protected function addMultiSafepayView(MultiSafepayConfigInterface $config)
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
