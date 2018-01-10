<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Method\View;

use H1\OroMultiSafepayBundle\Method\Config\MultiSafepayConfigInterface;
use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * The payment view in the checkout.
 * This also adds optional issuers when applicable.
 */
class MultiSafepayView implements PaymentMethodViewInterface
{
    /**
     * @var MultiSafepayConfigInterface
     */
    protected $config;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param MultiSafepayConfigInterface $config
     * @param FormFactoryInterface        $formFactory
     */
    public function __construct(MultiSafepayConfigInterface $config, FormFactoryInterface $formFactory)
    {
        $this->config = $config;
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function getOptions(PaymentContextInterface $context)
    {
        $formView = null;
        if (\count($this->config->getIssuers())) {
            $formView = $this->createIssuerChoiceField();
        }

        return [
            'formView' => $formView,
            'paymentMethod' => $this->config->getPaymentMethodIdentifier(),
            'gateway' => $this->config->getGateway()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getBlock()
    {
        return '_payment_methods_multi_safepay_widget';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->config->getLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getShortLabel(): string
    {
        return $this->config->getShortLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminLabel(): string
    {
        return $this->config->getAdminLabel();
    }

    /** {@inheritdoc} */
    public function getPaymentMethodIdentifier(): string
    {
        return $this->config->getPaymentMethodIdentifier();
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function createIssuerChoiceField(): \Symfony\Component\Form\FormView
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $formView = $this->formFactory->createNamed(
            'h1_multi_safepay_issuers',
            ChoiceType::class,
            null,
            [
                'choices' => array_filter(
                    $this->config->getAllIssuers(),
                    function ($issuerId) {
                        return \in_array($issuerId, $this->config->getIssuers(), true);
                    },
                    ARRAY_FILTER_USE_KEY
                ),
            ]
        )
        ->createView();

        return $formView;
    }
}
