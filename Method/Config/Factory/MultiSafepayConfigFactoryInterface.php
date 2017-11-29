<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Config\Factory;

use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;

/**
 * Interface MultiSafepayConfigFactoryInterface
 */
interface MultiSafepayConfigFactoryInterface
{
    /**
     * @param MultiSafepaySettings $settings
     * @return $this
     */
    public function create(MultiSafepaySettings $settings);
}