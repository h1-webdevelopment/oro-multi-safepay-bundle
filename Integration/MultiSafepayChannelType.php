<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Integration;
use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

/**
 * Class MultiSafepayChannelType
 */
class MultiSafepayChannelType implements ChannelInterface, IconAwareIntegrationInterface
{
    const TYPE = 'multi_safepay';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'h1.multi_safepay.channel_type.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return 'bundles/h1oromultisafepay/images/multi_safepay-icon.jpg';
    }
}
