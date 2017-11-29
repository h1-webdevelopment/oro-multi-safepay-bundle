<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Entity;

use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use Oro\Component\Testing\Unit\EntityTestCaseTrait;

class MultiSafepaySettingsTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;

    public function testAccessors()
    {
        static::assertPropertyCollections(new MultiSafepaySettings(), [
            ['labels', new LocalizedFallbackValue()],
            ['shortLabels', new LocalizedFallbackValue()],
        ]);
    }

    /*public function testGetSettingsBagReturnsCorrectObject()
    {
        $label = (new LocalizedFallbackValue())->setString('Money Order');

        $entity = new MultiSafepaySettings();
        $entity->addLabel($label);

        $settings = $entity->getSettingsBag();

        static::assertEquals([$label], $settings->get('labels'));
    }*/
}
