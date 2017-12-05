<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\Manager;

use H1\OroMultiSafepayBundle\Bridge\MultiSafepayApiClientBridgeInterface;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;

/**
 * Class MultiSafepayManager
 */
class MultiSafepayManager
{

    /**
     * @var MultiSafepayApiClientBridgeInterface
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiUrl = '';

    /**
     * MultiSafepayManager constructor.
     * @param MultiSafepayApiClientBridgeInterface $bridge
     */
    public function __construct(MultiSafepayApiClientBridgeInterface $bridge)
    {
        $this->client = $bridge;
    }

    /**
     * @param MultiSafepaySettings $settings
     *
     * @return $this
     */
    public function configure(MultiSafepaySettings $settings)
    {
        $this->setApiKey($settings->getApiKey());
        $this->setApiUrl(
            false === $settings->isTestMode() ?
                MultiSafepayApiClientBridgeInterface::API_URL_TESTING : MultiSafepayApiClientBridgeInterface::API_URL_LIVE
        );

        return $this;
    }

    /**
     * @param MultiSafepayConfigInterface $config
     * @internal param MultiSafepayConfig $settings
     *
     * @return $this
     */
    public function configureByConfig(MultiSafepayConfigInterface $config)
    {
        $this->setApiKey($config->getApiKey());
        $this->setApiUrl($config->getApiUrl());

        return $this;
    }

    /**
     * @param string $apiKey
     *
     * @return $this
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return MultiSafepayApiClientBridgeInterface
     * @throws \LogicException
     */
    public function getClient(): MultiSafepayApiClientBridgeInterface
    {
        if (null === $this->apiKey) {
            throw new \LogicException('Missing API Key, did you forget to call setApiKey?');
        }

        if (null === $this->apiUrl) {
            throw new \LogicException('Missing API URL, did you forget to call setApiUrl?');
        }

        return $this->client
            ->setApiUrl($this->getApiUrl())
            ->setApiKey($this->apiKey);
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return MultiSafepayManager
     */
    public function setApiUrl(string $apiUrl): MultiSafepayManager
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }
}
