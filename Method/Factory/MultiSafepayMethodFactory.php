<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\Factory;

use H1\OroMultiSafepayBundle\Manager\MultiSafepayManager;
use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use H1\OroMultiSafepayBundle\Method\MultiSafepay;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MultiSafepayMethodFactory
 */
class MultiSafepayMethodFactory implements MultiSafepayMethodFactoryInterface
{
    /**
     * @var MultiSafepayManager
     */
    private $multiSafepayManager;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MultiSafepayMethodFactory constructor.
     * @param MultiSafepayManager $multiSafepayManager '
     * @param RouterInterface     $router
     */
    public function __construct(MultiSafepayManager $multiSafepayManager, RouterInterface $router)
    {
        $this->multiSafepayManager = $multiSafepayManager;
        $this->router = $router;
    }


    /**
     * {@inheritdoc}
     */
    public function create(MultiSafepayConfigInterface $config)
    {
        return new MultiSafepay($config, $this->multiSafepayManager, $this->router);
    }
}
