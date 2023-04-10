<?php

namespace App\Form\Generic\Extension;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button form type extension.
 *
 * Add the button class.
 */
class ButtonTypeExtension extends AbstractTypeExtension
{

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', ['class' => 'btn']);
    }

    /**
     * {@inheritDoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class, SubmitType::class];
    }

}