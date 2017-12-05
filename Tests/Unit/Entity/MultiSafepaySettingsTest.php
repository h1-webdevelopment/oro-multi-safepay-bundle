<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
//@codingStandardsIgnoreFile
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
            ['shortLabels', new LocalizedFallbackValue()]
        ]);

        static::assertPropertyAccessors(new MultiSafepaySettings(), [
            ['apiKey', 'testApi'],
            ['testMode', true],
            ['gateway', 'IDEAL'],
        ]);
    }

    public function testGetSettingsBagReturnsCorrectObject()
    {
        $label = (new LocalizedFallbackValue())->setString('MultiSafepay');

        $entity = new MultiSafepaySettings();
        $entity->addLabel($label);

        $settings = $entity->getSettingsBag();

        static::assertEquals([$label], $settings->get('labels')->toArray());
    }
}
