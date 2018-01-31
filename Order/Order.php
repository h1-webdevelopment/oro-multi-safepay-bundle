<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle\Order;

use Doctrine\Common\Collections\ArrayCollection;

class Order
{
    /**
     * @var ArrayCollection
     */
    private $order;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->order = new ArrayCollection();
    }


    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addValues($key, $value)
    {
        $this->order->set($key, $value);

        return $this;
    }
}