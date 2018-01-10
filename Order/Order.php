<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Order;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Order
 */
class Order
{
    private $order;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->order = new ArrayCollection();
    }


    public function addValues($key, $value)
    {
        $this->order->set($key, $value);

//        dump($this->order); exit;
        return $this;
    }
}