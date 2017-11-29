<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\View\Factory;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\View\MultiSafepayView;
use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;

/**
 * Class MultiSafepayViewFactory
 */
class MultiSafepayViewFactory implements MultiSafepayViewFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(MultiSafepayConfigInterface $config)
    {
        return new MultiSafepayView($config);
    }
}