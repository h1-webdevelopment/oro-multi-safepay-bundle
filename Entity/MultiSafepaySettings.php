<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use H1\OroMultiSafepayBundle\Bridge\MultiSafepayApiClientBridgeInterface;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class MultiSafepaySettings
 * @ORM\Entity(repositoryClass="MultiSafepaySettingsRepository")
 */
class MultiSafepaySettings extends Transport
{
    const LABELS_KEY = 'labels';
    const SHORT_LABELS_KEY = 'short_labels';
    const TEST_MODE_KEY = 'test_mode';
    const API_KEY = 'api_key';
    const GATEWAY_KEY = 'gateway';
    const ISSUERS_KEY =  'issuers';

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="h1_multi_safepay_trans_label",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    private $labels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="h1_multi_safepay_short_label",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    private $shortLabels;

    /**
     * @var bool
     * @ORM\Column(name="msp_test_mode", type="boolean", options={"default"=false})
     */
    protected $testMode = false;

    /**
     * @var string
     * @ORM\Column(name="msp_api_key", type="string")
     */
    protected $apiKey = '';

    /**
     * @var string
     * @ORM\Column(name="msp_gateway", type="string", options={"default": ""})
     */
    protected $gateway = '';

    /**
     * The enabled / selected issuers
     *
     * @var array
     * @ORM\Column(name="msp_issuers", type="array")
     */
    protected $issuers = [];

    /**
     * @var array
     * @ORM\Column(name="msp_all_issuers", type="json_array")
     */
    protected $allIssuers = [];

    /**
     * @var ParameterBag
     */
    private $settings;

    /**
     * MultiSafepaySettings constructor.
     */
    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->shortLabels = new ArrayCollection();
        $this->issuers = [];
        $this->allIssuers = [];
    }

    /**
     * @return Collection|LocalizedFallbackValue[]
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param LocalizedFallbackValue $label
     *
     * @return $this
     */
    public function addLabel(LocalizedFallbackValue $label)
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
        }

        return $this;
    }

    /**
     * @param LocalizedFallbackValue $label
     *
     * @return $this
     */
    public function removeLabel(LocalizedFallbackValue $label)
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
        }

        return $this;
    }

    /**
     * @return Collection|LocalizedFallbackValue[]
     */
    public function getShortLabels()
    {
        return $this->shortLabels;
    }

    /**
     * @param LocalizedFallbackValue $label
     *
     * @return $this
     */
    public function addShortLabel(LocalizedFallbackValue $label)
    {
        if (!$this->shortLabels->contains($label)) {
            $this->shortLabels->add($label);
        }

        return $this;
    }

    /**
     * @param LocalizedFallbackValue $label
     *
     * @return $this
     */
    public function removeShortLabel(LocalizedFallbackValue $label)
    {
        if ($this->shortLabels->contains($label)) {
            $this->shortLabels->removeElement($label);
        }

        return $this;
    }

    /**
     * @return ParameterBag
     */
    public function getSettingsBag()
    {
        if (null === $this->settings) {
            $this->settings = new ParameterBag(
                [
                    self::LABELS_KEY => $this->getLabels(),
                    self::SHORT_LABELS_KEY => $this->getShortLabels(),
                    self::TEST_MODE_KEY =>  $this->isTestMode(),
                    self::API_KEY =>  $this->getApiKey(),
                    self::GATEWAY_KEY =>  $this->getGateway(),
                    self::ISSUERS_KEY => $this->getIssuers(),
                ]
            );
        }

        return $this->settings;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @param bool $testMode
     */
    public function setTestMode(bool $testMode)
    {
        $this->testMode = $testMode;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return (string) $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getGateway(): string
    {
        return (string) $this->gateway;
    }

    /**
     * @param string $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array
     */
    public function getIssuers(): array
    {
        // HACK: For some reason, the initial issuers is NULL by Symfony standards
        if (!\is_array($this->issuers)) {
            return [];
        }

        return $this->issuers;
    }

    /**
     * @param array $issuers
     * @return MultiSafepaySettings
     */
    public function setIssuers(array $issuers): MultiSafepaySettings
    {
        $this->issuers = $issuers;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllIssuers(): array
    {
        return $this->allIssuers;
    }

    /**
     * @param array|string $allIssuers
     */
    public function setAllIssuers($allIssuers)
    {
        if (\is_string($allIssuers)) {
            $allIssuers = json_decode($allIssuers);
        }
        $this->allIssuers = $allIssuers;
    }

    /**
     * Return API Url endpoint based on testing mode
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return false === $this->isTestMode() ?
            MultiSafepayApiClientBridgeInterface::API_URL_TESTING : MultiSafepayApiClientBridgeInterface::API_URL_LIVE;
    }
}
