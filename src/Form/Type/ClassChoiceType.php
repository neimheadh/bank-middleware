<?php

namespace App\Form\Type;

use App\Form\Loader\ClassListChoiceLoader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class list select type.
 */
class ClassChoiceType extends AbstractType
{

    /**
     * Namespace option name.
     */
    public const OPTION_NAMESPACE = 'namespace';

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('extended', false);
        $resolver->setDefault('multiple', false);
        $resolver->setDefault(
            'choice_loader',
            function (Options $options) {
                return ChoiceList::loader(
                    $this,
                    new ClassListChoiceLoader($options['namespace']),
                    [$options['namespace']]
                );
            }
        );

        $resolver->setRequired([self::OPTION_NAMESPACE]);
        $resolver->setAllowedTypes(self::OPTION_NAMESPACE, ['string']);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'app_class_choice';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

}