<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\Bridge;

/**
 * Interface MultiSafepayApiClientBridgeInterface
 */
interface MultiSafepayApiClientBridgeInterface
{
    const API_URL_LIVE = 'https://testapi.multisafepay.com/v1/json/';
    const API_URL_TESTING = 'https://api.multisafepay.com/v1/json/';

    /**
     * Set the api key to use for the current client.
     *
     * @param string $apiKey
     *
     * @return MultiSafepayApiClientBridgeInterface
     */
    public function setApiKey(string $apiKey): MultiSafepayApiClientBridgeInterface;

    /**
     * The API url is usually the production URL or Live URL
     *
     * @param string $apiUrl
     *
     * @return MultiSafepayApiClientBridgeInterface
     */
    public function setApiUrl(string $apiUrl): MultiSafepayApiClientBridgeInterface;

    /**
     * Return a list of issuers
     *
     * @return array
     */
    public function getIssuers(): array;

    /**
     * Return a list of gateways
     *
     * @return array
     */
    public function getGateways(): array;

    /**
     * Create a new order in MultiSafepay
     * @see https://www.multisafepay.com/documentation/doc/API-Reference/#API-Reference-Createanorder
     *
     * @param array $orders
     * @return object
     */
    public function postOrders(array $orders);

    /**
     * Find/Get an order by its transactionId
     *
     * @param string $transactionId
     * @return object
     */
    public function getOrder(string $transactionId);
}
