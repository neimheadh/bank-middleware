<?php

namespace App\Form\Generic\Extension;

use Doctrine\DBAL\Types\TextType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Input type extension.
 *
 * Add form control class to text input.
 */
class InputExtension extends AbstractTypeExtension
{

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', ['class' => 'form-control']);
    }

    /**
     * {@inheritDoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            ModelAutocompleteType::class,
            NumberType::class,
            TextType::class,
        ];
    }

}