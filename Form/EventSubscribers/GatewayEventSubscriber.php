<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Form\EventSubscribers;

use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Manager\MultiSafepayManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class GatewayEventListener
 */
class GatewayEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var MultiSafepayManager
     */
    private $multiSafepayManager;

    /**
     * GatewayEventSubscriber constructor.
     * @param MultiSafepayManager $multiSafepayManager
     */
    public function __construct(MultiSafepayManager $multiSafepayManager)
    {
        $this->multiSafepayManager = $multiSafepayManager;
    }

    /**
     * @param FormEvent $formEvent
     * @return void
     */
    public function preSetData(FormEvent $formEvent)
    {
        /** @var MultiSafepaySettings $settings */
        $settings = $formEvent->getData();

        if (null === $settings || '' === $settings->getApiKey()) {
            return;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $formEvent
            ->getForm()
            ->add(
                'gateway',
                ChoiceType::class,
                [
                    'choices' =>  $this->getGatewayChoices($settings),
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * @return array
     */
    private function getGatewayChoices(MultiSafepaySettings $settings): array
    {
        try {
            $gateways = $this->multiSafepayManager
                ->configure($settings)
                ->getClient()
                ->getGateways();
        } catch (\Exception $e) {
            // TODO: Show exception to client
            return [];
        }

        return array_combine(
            array_column($gateways, 'id'),
            array_column($gateways, 'description')
        );
    }
}
