<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle;

use H1\OroMultiSafepayBundle\DependencyInjection\CompilerPass\AddMultiSafepaySettingsEventSubscribersCompilerPass;
use H1\OroMultiSafepayBundle\DependencyInjection\CompilerPass\SetupMultiSafepayBridgeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * MultiSafepay Integration Bundle
 */
class H1OroMultiSafepayBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SetupMultiSafepayBridgeCompilerPass());
        $container->addCompilerPass(new AddMultiSafepaySettingsEventSubscribersCompilerPass());
    }
}
