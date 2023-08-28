<?php

namespace App\Form\Generic\Type;

use App\Form\Generic\DataTransformer\ArrayToYamlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Yaml input form type.
 */
class YamlType extends AbstractType
{

    /**
     * @param ArrayToYamlTransformer $transformer Array to Yaml transformer.
     */
    public function __construct(
        private readonly ArrayToYamlTransformer $transformer,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->addModelTransformer($this->transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', ['class' => 'prism-live language-yaml']);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'app_generic_yaml';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return TextareaType::class;
    }

}