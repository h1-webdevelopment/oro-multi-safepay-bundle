<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Config\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettingsRepository;
use H1\OroMultiSafepayBundle\Method\Config\Factory\MultiSafepayConfigFactoryInterface;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Class MultiSafepayConfigProvider
 */
class MultiSafepayConfigProvider implements MultiSafepayConfigProviderInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var MultiSafepayConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * @var MultiSafepayConfigInterface[]
     */
    protected $configs;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     * @param MultiSafepayConfigProviderInterface $configFactory
     */
    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        MultiSafepayConfigFactoryInterface $configFactory
    ) {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->configFactory = $configFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentConfigs()
    {
        $configs = [];

        $settings = $this->getEnabledIntegrationSettings();

        foreach ($settings as $setting) {
            $config = $this->configFactory->create($setting);

            $configs[$config->getPaymentMethodIdentifier()] = $config;
        }

        return $configs;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentConfig($identifier)
    {
        $paymentConfigs = $this->getPaymentConfigs();

        if ([] === $paymentConfigs || false === array_key_exists($identifier, $paymentConfigs)) {
            return null;
        }

        return $paymentConfigs[$identifier];
    }

    /**
     * {@inheritDoc}
     */
    public function hasPaymentConfig($identifier)
    {
        return null !== $this->getPaymentConfig($identifier);
    }

    /**
     * @return MultiSafepaySettings[]
     */
    protected function getEnabledIntegrationSettings()
    {
        try {
            /** @var MultiSafepaySettingsRepository $repository */
            $repository = $this->doctrine
                ->getManagerForClass(MultiSafepaySettings::class)
                ->getRepository(MultiSafepaySettings::class);

            return $repository->getEnabledSettings();
        } catch (\UnexpectedValueException $e) {
            $this->logger->critical($e->getMessage());

            return [];
        }
    }
}