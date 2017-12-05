<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Config;

use Oro\Bundle\PaymentBundle\Method\Config\PaymentConfigInterface;

/**
 * Interface MultiSafepayConfigInterface
 */
interface MultiSafepayConfigInterface extends PaymentConfigInterface
{
    /**
     * Return a valid API Key, can be set in the integration settings.
     *
     * @return string
     */
    public function getApiKey(): string;

    /**
     * Return the current API URL as specified by the integration settings.
     *
     * @return string
     */
    public function getApiUrl(): string;

    /**
     * Return a list of *enabled* issuers available for the current payment gateway.
     *
     * These are chosen by the user in the integration setup.
     *
     * @return array
     */
    public function getIssuers(): array ;

    /**
     * Return a list of *all* issuers available for the current payment gateway.
     *
     * @return array
     */
    public function getAllIssuers(): array ;
}
