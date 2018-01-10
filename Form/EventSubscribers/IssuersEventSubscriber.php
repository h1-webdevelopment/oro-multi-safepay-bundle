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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class IssuersEventSubscriber
 */
class IssuersEventSubscriber implements EventSubscriberInterface
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

        if (null === $settings || '' === $settings->getApiKey() || $settings->getGateway() !== 'IDEAL') {
            return;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $formEvent
            ->getForm()
            ->add(
                'issuers',
                ChoiceType::class,
                [
                    'choices' =>  $this->getIssuers($settings),
                    'multiple' => true,
                ]
            )
            ->add(
                'allIssuers',
                HiddenType::class,
                [
                    'data' => json_encode($this->getIssuers($settings)),
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
    private function getIssuers(MultiSafepaySettings $settings): array
    {
        try {
            $gateways = $this->multiSafepayManager
                ->configure($settings)
                ->getClient()
                ->getIssuers();
        } catch (\Exception $e) {
            // TODO: Show exception to client
            return [];
        }

        return array_combine(
            array_column($gateways, 'code'),
            array_column($gateways, 'description')
        );
    }
}
