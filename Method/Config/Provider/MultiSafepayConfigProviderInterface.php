<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Config\Provider;

use H1\OroMultiSafepayBundle\Method\Config\Factory\MultiSafepayConfigFactoryInterface;

/**
 * Interface MultiSafepayConfigProviderInterface
 */
interface MultiSafepayConfigProviderInterface
{
    /**
     * @return MultiSafepayConfigFactoryInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     * @return MultiSafepayConfigFactoryInterface|null
     */
    public function getPaymentConfig($identifier);

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasPaymentConfig($identifier);
}
