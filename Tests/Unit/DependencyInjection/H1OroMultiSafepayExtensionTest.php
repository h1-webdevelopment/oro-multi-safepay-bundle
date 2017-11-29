<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\DependencyInjection;

use H1\OroMultiSafepayBundle\DependencyInjection\H1OroMultiSafepayExtension;
use Oro\Bundle\TestFrameworkBundle\Test\DependencyInjection\ExtensionTestCase;

class H1OroMultiSafepayExtensionTest extends ExtensionTestCase
{
    public function testLoad()
    {
        $this->loadExtension(new H1OroMultiSafepayExtension());

        $expectedDefinitions = [
            'h1_multi_safepay.payment_method_provider.multi_safepay',
            'h1_multi_safepay.payment_method_view_provider.multi_safepay',
            'h1_multi_safepay.integration.channel',
            'h1_multi_safepay.integration.transport',
            'h1_multi_safepay.payment_method.config.provider',
            'h1_multi_safepay.factory.multi_safepay_config',
            'h1_multi_safepay.generator.multi_safepay_config_identifier',
            'h1_multi_safepay.factory.method.multi_safepay',
            'h1_multi_safepay.factory.method_view.multi_safepay',
//            'h1_multi_safepay.repository.money_settings',
        ];
        $this->assertDefinitionsLoaded($expectedDefinitions);

        $expectedParameters = [
            'h1_multi_safepay.method.identifier_prefix.multi_safepay'
        ];
        $this->assertParametersLoaded($expectedParameters);
    }

    /**
     * Test Get Alias
     */
    public function testGetAlias()
    {
        $extension = new H1OroMultiSafepayExtension();
        $this->assertEquals(H1OroMultiSafepayExtension::ALIAS, $extension->getAlias());
    }
}
