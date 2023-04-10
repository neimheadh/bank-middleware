<?php

namespace App\Form\Generic\Type;

use App\Form\Generic\ChoiceLoader\ClassListChoiceLoader;
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
     * How deep the namespace directory is parsed option name.
     */
    public const OPTION_DEPTH = 'depth';

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
                    new ClassListChoiceLoader(
                        $options[self::OPTION_NAMESPACE],
                        $options[self::OPTION_DEPTH]
                    ),
                    [
                        $options[self::OPTION_NAMESPACE],
                        $options[self::OPTION_DEPTH],
                    ]
                );
            }
        );

        $resolver->setRequired([self::OPTION_NAMESPACE]);
        $resolver->setAllowedTypes(self::OPTION_NAMESPACE, ['string']);

        $resolver->setDefault(self::OPTION_DEPTH, -1);
        $resolver->setAllowedTypes(self::OPTION_DEPTH, ['int']);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
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