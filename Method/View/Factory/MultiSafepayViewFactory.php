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
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class MultiSafepayViewFactory
 */
class MultiSafepayViewFactory implements MultiSafepayViewFactoryInterface
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * MultiSafepayViewFactory constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(MultiSafepayConfigInterface $config)
    {
        return new MultiSafepayView($config, $this->formFactory);
    }
}
