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
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
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
     * @var DoctrineHelper
     */
    private $doctrineHelper;

    /**
     * MultiSafepayMethodFactory constructor.
     * @param MultiSafepayManager $multiSafepayManager '
     * @param RouterInterface $router
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(MultiSafepayManager $multiSafepayManager, RouterInterface $router, DoctrineHelper $doctrineHelper)
    {
        $this->multiSafepayManager = $multiSafepayManager;
        $this->router = $router;
        $this->doctrineHelper = $doctrineHelper;
    }


    /**
     * {@inheritdoc}
     */
    public function create(MultiSafepayConfigInterface $config)
    {
        return new MultiSafepay($config, $this->multiSafepayManager, $this->router, $this->doctrineHelper);
    }
}
