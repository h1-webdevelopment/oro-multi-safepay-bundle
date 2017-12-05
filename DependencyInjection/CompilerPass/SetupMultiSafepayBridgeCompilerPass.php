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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class SetupMultiSafepayBridgeCompilerPass
 */
class SetupMultiSafepayBridgeCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $definition = $container->getDefinition('h1_multi_safepay.bridge.default');

        if (!class_exists('\\MultiSafepayAPI\\Client')) {
            return;
        }

        $clientDefinition = new Definition(\MultiSafepayAPI\Client::class);
        $container->register('h1_multi_safepay.client.default', $clientDefinition->getClass());

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $definition->replaceArgument(0, new Reference('h1_multi_safepay.client.default'));
    }
}
