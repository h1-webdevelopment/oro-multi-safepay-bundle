<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

//@codingStandardsIgnoreFile
namespace H1\OroMultiSafepayBundle\Tests\Unit\Form\Type;

use Oro\Bundle\LocaleBundle\Form\Type\LocalizedFallbackValueCollectionType;
use Oro\Bundle\LocaleBundle\Tests\Unit\Form\Type\Stub\LocalizedFallbackValueCollectionTypeStub;
use H1\OroMultiSafepayBundle\Entity\MultiSafepaySettings;
use H1\OroMultiSafepayBundle\Form\Type\MultiSafepaySettingsType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;

class MultiSafepaySettingsTypeTest extends FormIntegrationTestCase
{
    /** @var MultiSafepaySettingsType */
    private $formType;

    protected function setUp()
    {
        parent::setUp();

        $this->formType = new MultiSafepaySettingsType();
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [
                    LocalizedFallbackValueCollectionType::NAME => new LocalizedFallbackValueCollectionTypeStub(),
                ],
                []
            ),
            new ValidatorExtension(Validation::createValidator())
        ];
    }

    public function testSubmitValid()
    {
        $submitData = [
            'labels' => [['string' => 'first label']],
            'shortLabels' => [['string' => 'short label']],
            'testMode' => true,
            'apiKey' => 'testApiKey',
        ];

        $settings = new MultiSafepaySettings();

        $form = $this->factory->create($this->formType, $settings);
        $form->submit($submitData);

        $this->assertTrue($form->isValid());
        $this->assertEquals($settings, $form->getData());
    }

    public function testGetBlockPrefixReturnsCorrectString()
    {
        static::assertSame('h1_multi_safepay_setting_type', $this->formType->getBlockPrefix());
    }

    public function testConfigureOptions()
    {
        /** @var OptionsResolver|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->createMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $resolver->expects(static::once())
            ->method('setDefaults')
            ->with([
                'data_class' => MultiSafepaySettings::class
            ]);
        $this->formType->configureOptions($resolver);
    }
}
