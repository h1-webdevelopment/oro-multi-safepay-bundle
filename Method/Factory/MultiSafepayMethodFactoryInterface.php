<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle\Method\Factory;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;

interface MultiSafepayMethodFactoryInterface
{
    /**
     * @param MultiSafepayConfigInterface $config
     * @return PaymentMethodInterface
     */
    public function create(MultiSafepayConfigInterface $config);
}