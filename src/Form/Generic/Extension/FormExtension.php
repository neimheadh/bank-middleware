<?php

namespace App\Form\Generic\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type extension.
 *
 * Configure default options for form types.
 */
class FormExtension extends AbstractTypeExtension implements
    FormTypeExtensionInterface
{

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'admin');
    }

    /**
     * {@inheritDoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

}