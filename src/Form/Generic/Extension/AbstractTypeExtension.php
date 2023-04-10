<?php

namespace App\Form\Generic\Extension;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type extension.
 */
abstract class AbstractTypeExtension implements FormTypeExtensionInterface
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(
        FormView $view,
        FormInterface $form,
        array $options
    ) {
    }

    /**
     * Configure extension options.
     *
     * @param OptionsResolver $resolver Options resolver.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

}