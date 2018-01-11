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
use OutOfBoundsException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @throws UnexpectedTypeException
     * @throws LogicException
     * @throws AlreadySubmittedException
     * @throws OutOfBoundsException
     */
    public function preSetData(FormEvent $formEvent)
    {
        /** @var MultiSafepaySettings $settings */
        $settings = $formEvent->getData();

        if (null === $settings || '' === $settings->getApiKey()) {
            return;
        }

        try {
            $gatewayChoices = $this->getGatewayChoices($settings);
            $gatewayError = false;
        } catch (BadRequestHttpException $e) {
            $gatewayChoices = [];
            $gatewayError = $e->getMessage();
        }

        $form = $formEvent->getForm();
        $form->add(
            'gateway',
            ChoiceType::class,
            [
                'choices' => $gatewayChoices,
            ]
        );

        if ($gatewayError && !$form->isSubmitted()) {
            $form->get('gateway')
                ->addError(new FormError($gatewayError));
        }
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
            throw new BadRequestHttpException($e->getMessage());
        }

        return array_combine(
            array_column($gateways, 'id'),
            array_column($gateways, 'description')
        );
    }
}
