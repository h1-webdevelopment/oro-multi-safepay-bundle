<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Method\Config\Factory;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Method\Config\Factory\MultiSafepayConfigFactory;
use H1\OroMultiSafepayBundle\Method\Config\Factory\MultiSafepayConfigFactoryInterface;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfig;

class MultiSafepayConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MultiSafepayConfigFactoryInterface
     */
    private $testedFactory;

    /**
     * @var LocalizationHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localizationHelperMock;

    /**
     * @var IntegrationIdentifierGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $integrationIdentifierGeneratorMock;

    public function setUp()
    {
        $this->localizationHelperMock = $this->createMock(LocalizationHelper::class);
        $this->integrationIdentifierGeneratorMock = $this->createMock(IntegrationIdentifierGeneratorInterface::class);

        $this->testedFactory = new MultiSafepayConfigFactory(
            $this->localizationHelperMock,
            $this->integrationIdentifierGeneratorMock
        );
    }

    public function testCreate()
    {
        $channelName = 'someChannelName';
        $label = 'someLabel';
        $paymentMethodId = 'paymentMethodId';
        $issuers = ['issuerId'];
        $allIssuers = ['issuerId', 'issuerKey'];
        $gatewayKey = 'gateway';
        $apiKey = 'apiKey';
        $apiUrl = 'apiUrl';

        $paymentSettingsMock = $this->createMultiSafepaySettingsMock();
        $channelMock = $this->createChannelMock();
        $labelsCollection = $this->createLabelsCollectionMock();
        $shortLabelsCollection = $this->createLabelsCollectionMock();

        $this->integrationIdentifierGeneratorMock
            ->expects(static::once())
            ->method('generateIdentifier')
            ->with($channelMock)
            ->willReturn($paymentMethodId);

        $this->localizationHelperMock
            ->expects(static::at(0))
            ->method('getLocalizedValue')
            ->with($labelsCollection)
            ->willReturn($label);

        $this->localizationHelperMock
            ->expects(static::at(1))
            ->method('getLocalizedValue')
            ->with($shortLabelsCollection)
            ->willReturn($label);

        $channelMock
            ->expects(static::once())
            ->method('getName')
            ->willReturn($channelName);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getChannel')
            ->willReturn($channelMock);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getLabels')
            ->willReturn($labelsCollection);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getShortLabels')
            ->willReturn($shortLabelsCollection);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getGateway')
            ->willReturn($gatewayKey);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getApiKey')
            ->willReturn($apiKey);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getApiUrl')
            ->willReturn($apiUrl);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getIssuers')
            ->willReturn($issuers);

        $paymentSettingsMock
            ->expects(static::once())
            ->method('getAllIssuers')
            ->willReturn($allIssuers);

        $expectedSettings = new MultiSafepayConfig(
            [
                MultiSafepayConfig::LABEL_KEY => $label,
                MultiSafepayConfig::SHORT_LABEL_KEY => $label,
                MultiSafepayConfig::ADMIN_LABEL_KEY => $channelName,
                MultiSafepayConfig::GATEWAY_KEY => $gatewayKey,
                MultiSafepayConfig::API_KEY => $apiKey,
                MultiSafepayConfig::API_URL => $apiUrl,
                MultiSafepayConfig::ISSUERS_KEY => $issuers,
                MultiSafepayConfig::ALL_ISSUERS => $allIssuers,
                MultiSafepayConfig::PAYMENT_METHOD_IDENTIFIER_KEY => $paymentMethodId,
            ]
        );

        $actualSettings = $this->testedFactory->create($paymentSettingsMock);

        static::assertEquals($expectedSettings, $actualSettings);
    }

    /**
     * @return MultiSafepaySettings|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createMultiSafepaySettingsMock()
    {
        return $this->createMock(MultiSafepaySettings::class);
    }

    /**
     * @return Channel|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createChannelMock()
    {
        return $this->createMock(Channel::class);
    }

    /**
     * @return Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createLabelsCollectionMock()
    {
        return $this->createMock(Collection::class);
    }
}
