<?php

namespace H1\OroMultiSafepayBundle\Tests\Unit\Integration;

use H1\OroMultiSafepayBundle\Integration\MultiSafepayChannelType;

class MultiSafepayChannelTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var MultiSafepayChannelType */
    private $channel;

    protected function setUp()
    {
        $this->channel = new MultiSafepayChannelType();
    }

    public function testGetLabelReturnsCorrectString()
    {
        static::assertSame('h1.multi_safepay.channel_type.label', $this->channel->getLabel());
    }

    public function testGetIconReturnsCorrectString()
    {
        static::assertSame(
            'bundles/h1oromultisafepay/images/multi_safepay-icon.jpg',
            $this->channel->getIcon()
        );
    }
}
