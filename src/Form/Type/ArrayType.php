<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

/**
 * Form array type.
 *
 * @todo Create a real form array type.
 */
class ArrayType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->addModelTransformer(
            new CallbackTransformer(
                fn(?array $data) => $data !== null ? Yaml::dump($data, 10, 2) : '',
                fn(?string $yaml) => $yaml !== null ? Yaml::parse($yaml) : [],
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', [
            'class' => 'monospace',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return TextareaType::class;
    }

}