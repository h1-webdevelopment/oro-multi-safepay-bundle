<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace H1\OroMultiSafepayBundle\Order\Decorator;

use H1\OroMultiSafepayBundle\Order\Order;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;

/**
 * Adds required customer information for a single order
 */
class CustomerDataDecorator extends AbstractOrderDataDecorator
{
    /**
     * {@inheritdoc}
     */
    public function decorate(Order $order)
    {
        /** @var CustomerUser $customer */
        $customer = $this->paymentTransaction->getFrontendOwner();

        $customerData = [
            'first_name' => $customer->getFirstName(),
            'last_name' => $customer->getLastName(),
            'address1' => 'Kraanspoor',
            'address2' => '',
            'house_number' => '39',
            'zip_code' => '1032 SC',
            'city' => 'Amsterdam',
            'state' => '',
            'country' => 'NL',
            'phone' => '0208500500',
            'email' => 'test@test.nl',
        ];


        $order->addValues(
            'customer',
            $customerData
        );
    }
}