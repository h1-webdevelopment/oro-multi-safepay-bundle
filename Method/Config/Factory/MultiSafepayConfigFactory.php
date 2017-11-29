<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Config\Factory;

use Doctrine\Common\Collections\Collection;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfig;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;

/**
 * Class MultiSafepayConfigFactory
 */
class MultiSafepayConfigFactory implements MultiSafepayConfigFactoryInterface
{
    /**
     * @var LocalizationHelper
     */
    private $localizationHelper;

    /**
     * @var IntegrationIdentifierGeneratorInterface
     */
    private $identifierGenerator;

    /**
     * @param LocalizationHelper $localizationHelper
     * @param IntegrationIdentifierGeneratorInterface $identifierGenerator
     */
    public function __construct(
        LocalizationHelper $localizationHelper,
        IntegrationIdentifierGeneratorInterface $identifierGenerator
    ) {
        $this->localizationHelper = $localizationHelper;
        $this->identifierGenerator = $identifierGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function create(MultiSafepaySettings $settings)
    {
        $params = [];
        $channel = $settings->getChannel();

        $params[MultiSafepayConfig::LABEL_KEY] = $this->getLocalizedValue($settings->getLabels());
        $params[MultiSafepayConfig::SHORT_LABEL_KEY] = $this->getLocalizedValue($settings->getShortLabels());
        $params[MultiSafepayConfig::ADMIN_LABEL_KEY] = $channel->getName();
        $params[MultiSafepayConfig::PAYMENT_METHOD_IDENTIFIER_KEY] =
            $this->identifierGenerator->generateIdentifier($channel);

        return new MultiSafepayConfig($params);
    }

    /**
     * @param Collection $values
     *
     * @return string
     */
    private function getLocalizedValue(Collection $values)
    {
        return (string)$this->localizationHelper->getLocalizedValue($values);
    }
}