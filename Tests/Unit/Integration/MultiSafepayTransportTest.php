<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Integration;

use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Form\Type\MultiSafepaySettingsType;
use H1\OroMultiSafepayBundle\Integration\MultiSafepayTransport;

class MultiSafepayTransportTest extends \PHPUnit_Framework_TestCase
{
    /** @var MultiSafepayTransport */
    private $transport;

    protected function setUp()
    {
        $this->transport = new MultiSafepayTransport();
    }

    public function testInitCompiles()
    {
        $this->transport->init(new MultiSafepaySettings());
    }

    public function testGetSettingsFormTypeReturnsCorrectName()
    {
        static::assertSame(MultiSafepaySettingsType::class, $this->transport->getSettingsFormType());
    }

    public function testGetSettingsEntityFQCNReturnsCorrectName()
    {
        static::assertSame(MultiSafepaySettings::class, $this->transport->getSettingsEntityFQCN());
    }

    public function testGetLabelReturnsCorrectString()
    {
        static::assertSame('h1.multi_safepay.settings.transport.label', $this->transport->getLabel());
    }
}
