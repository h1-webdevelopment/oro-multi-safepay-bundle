<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds form extra's that should be available in the Form\Type\MultiSafepaySettingsType.
 * These can be anything such as available gateways and issuers for specific gateways.
 */
class AddMultiSafepaySettingsEventSubscribersCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('h1_multi_safepay_form_event_listener');

        if (!\count($taggedServices)) {
            return;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $gatewayFormDefinition = $container->getDefinition('h1_multi_safepay.form.type.settings');

        foreach ($taggedServices as $serviceId => $serviceType) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $gatewayFormDefinition->addMethodCall(
                'addEventSubscriber',
                [ new Reference($serviceId) ]
            );
        }
    }
}
