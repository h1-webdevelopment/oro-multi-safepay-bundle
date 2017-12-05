<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Config;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class MultiSafepayConfig
 */
class MultiSafepayConfig extends ParameterBag implements MultiSafepayConfigInterface
{
    const LABEL_KEY = 'label';
    const SHORT_LABEL_KEY = 'short_label';
    const ADMIN_LABEL_KEY = 'admin_label';
    const PAYMENT_METHOD_IDENTIFIER_KEY = 'payment_method_identifier';
    const ISSUER_IDENTIFIER_KEY = 'issuer_method_identifier';
    const TEST_MODE_KEY = 'test_mode';
    const API_KEY = 'api_key';
    const GATEWAY_KEY = 'gateway';
    const API_URL = 'api_url';
    const ALL_ISSUERS = 'all_issuers';
    const ISSUERS_KEY = 'issuers';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return (string) $this->get(self::LABEL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getShortLabel()
    {
        return (string) $this->get(self::SHORT_LABEL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminLabel()
    {
        return (string) $this->get(self::ADMIN_LABEL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethodIdentifier()
    {
        return (string) $this->get(self::PAYMENT_METHOD_IDENTIFIER_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuerIdentifier(): string
    {
        return (string) $this->get(self::ISSUER_IDENTIFIER_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getApiUrl(): string
    {
        return (string) $this->get(self::API_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey(): string
    {
        return (string) $this->get(self::API_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuers(): array
    {
        return (array) $this->get(self::ISSUERS_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIssuers(): array
    {
        return (array) $this->get(self::ALL_ISSUERS);
    }
}
