<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Bridge;

/**
 * Provides integration with MultiSafepay through a customisable client bridge.
 */
class MultiSafepayApiBridge implements MultiSafepayApiClientBridgeInterface
{
    /**
     * @var \MultiSafepayAPI\Client
     */
    private $client;

    /**
     * MultiSafepayClientBridge constructor.
     * @param \MultiSafepayAPI\Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuers(): array
    {
        return $this->client->issuers->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getGateways(): array
    {
        return $this->client->gateways->get();
    }

    /**
     * {@inheritdoc}
     */
    public function setApiKey(string $apiKey): MultiSafepayApiClientBridgeInterface
    {
        $this->client->setApiKey($apiKey);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiUrl(string $apiUrl): MultiSafepayApiClientBridgeInterface
    {
        $this->client->setApiUrl($apiUrl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function postOrders(array $orders)
    {
        try {
            $result = $this->client->orders->post($orders);
        } catch (\Exception $e) {
            dump($e);
            exit;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(string $transactionId)
    {
        try {
            $result = $this->client->orders->get('orders', $transactionId);
        } catch (\Exception $e) {
            dump($e);
            exit;
        }

        return $result;
    }
}
