<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\Integration;

use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Form\Type\MultiSafepaySettingsType;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;


class MultiSafepayTransport implements TransportInterface
{

    /**
     * @param Transport $transportEntity
     */
    public function init(Transport $transportEntity)
    {
        // TODO: Implement init() method.
    }

    /**
     * Returns label for UI
     *
     * @return string
     */
    public function getLabel()
    {
        return 'h1.multi_safepay.settings.transport.label';
    }

    /**
     * Returns form type name needed to setup transport
     *
     * @return string
     */
    public function getSettingsFormType()
    {
        return MultiSafepaySettingsType::class;
    }

    /**
     * Returns entity name needed to store transport settings
     *
     * @return string
     */
    public function getSettingsEntityFQCN()
    {
        return MultiSafepaySettings::class;
    }
}