<?php

namespace App\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Array form type.
 */
class ArrayType extends AbstractType implements EventSubscriberInterface,
                                                DataTransformerInterface
{

    /**
     * Add button label option.
     */
    public const OPTION_ADD_LABEL = 'btn-add-label';

    /**
     * Element name label option.
     */
    public const OPTION_NAME_LABEL = 'element-name-label';

    /**
     * Element name placeholder option.
     */
    public const OPTION_NAME_PLACEHOLDER = 'element-name-placeholder';

    /**
     * Remove button label option.
     */
    public const OPTION_REMOVE_LABEL = 'btn-remove-label';

    /**
     * Element type label option.
     */
    public const OPTION_TYPE_LABEL = 'element-type-label';

    /**
     * Element type placeholder option.
     */
    public const OPTION_TYPE_PLACEHOLDER = 'element-type-placeholder';

    /**
     * Element type options.
     */
    public const OPTION_TYPES = 'element-types';

    /**
     * Element value label option.
     */
    public const OPTION_VALUE_LABEL = 'element-value-label';

    /**
     * Element value placeholder option.
     */
    public const OPTION_VALUE_PLACEHOLDER = 'element-value-placeholder';

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->addEventSubscriber($this);
        $builder->addModelTransformer($this);

        $this->setForm($builder->getForm(), $options);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        $view->vars['add_label'] = $options[self::OPTION_ADD_LABEL];
        $view->vars['remove_label'] = $options[self::OPTION_REMOVE_LABEL];
        $view->vars['name_label'] = $options[self::OPTION_NAME_LABEL];
        $view->vars['name_placeholder'] = $options[self::OPTION_NAME_PLACEHOLDER];
        $view->vars['type_label'] = $options[self::OPTION_TYPE_LABEL];
        $view->vars['type_placeholder'] = $options[self::OPTION_TYPE_PLACEHOLDER];
        $view->vars['types'] = $options[self::OPTION_TYPES];
        $view->vars['value_label'] = $options[self::OPTION_VALUE_LABEL];
        $view->vars['value_placeholder'] = $options[self::OPTION_VALUE_PLACEHOLDER];
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'required' => false,
            'translation_domain' => 'form',

            self::OPTION_ADD_LABEL => 'array.label.add',
            self::OPTION_REMOVE_LABEL => 'array.label.remove',
            self::OPTION_NAME_LABEL => 'array.label.name',
            self::OPTION_NAME_PLACEHOLDER => 'array.placeholder.name',
            self::OPTION_TYPE_LABEL => 'array.label.type',
            self::OPTION_TYPE_PLACEHOLDER => 'array.placeholder.type',
            self::OPTION_TYPES => [
                'string' => 'array.type.string',
                'integer' => 'array.type.integer',
                'double' => 'array.type.double',
                'boolean' => 'array.type.boolean',
            ],
            self::OPTION_VALUE_LABEL => 'array.label.value',
            self::OPTION_VALUE_PLACEHOLDER => 'array.placeholder.value',
        ]);

        $resolver->setAllowedTypes(self::OPTION_TYPES, ['string[]']);
        foreach (
            [
                self::OPTION_ADD_LABEL,
                self::OPTION_REMOVE_LABEL,
                self::OPTION_NAME_LABEL,
                self::OPTION_NAME_PLACEHOLDER,
                self::OPTION_TYPE_LABEL,
                self::OPTION_TYPE_PLACEHOLDER,
                self::OPTION_VALUE_LABEL,
                self::OPTION_VALUE_PLACEHOLDER,
            ] as $option
        ) {
            $resolver->setAllowedTypes($option, ['string']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'app_array';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * Handle form post set data.
     *
     * @param PostSetDataEvent $event Post set data event.
     *
     * @return void
     */
    public function onPostSetData(PostSetDataEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (is_array($data)) {
            $this->setForm(
                $form,
                $form->getConfig()->getOptions(),
                $this->transform($data)
            );
        }
    }

    /**
     * Handle form pre submit event.
     *
     * @param PreSubmitEvent $event Pre submit event.
     *
     * @return void
     */
    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $form = $event->getForm();

        $this->setForm(
            $form,
            $form->getConfig()->getOptions(),
            $event->getData()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_combine(
                array_map(
                    fn($item) => $item['name'],
                    $value
                ),
                array_map(
                    fn($item) => match (gettype($item['type'])) {
                        'integer' => intval($item['value']),
                        'boolean' => boolval($item['value']),
                        'double' => floatval($item['value']),
                        default => $value
                    },
                    $value
                )
            );
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transform(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_map(
                fn($val, $name) => [
                    'name' => $name,
                    'value' => is_array($val) ? $this->transform($val) : $val,
                    'type' => gettype($val),
                ],
                $value,
                array_keys($value)
            );
        }

        return $value;
    }

    /**
     * Add form element.
     *
     * @param FormInterface $form    Form.
     * @param mixed         $name    Element name.
     * @param array         $options Form options.
     * @param array         $data    Element data.
     *
     * @return void
     */
    private function addElement(
        FormInterface $form,
        mixed $name,
        array $options,
        array $data = [],
    ): void {
        $form->add($name, FormType::class);
        $element = $form->get($name);

        $element->add('name', TextType::class, [
            'attr' => [
                'placeholder' => 'array.placeholder.name',
            ],
            'label' => false,
            'translation_domain' => $options['translation_domain'],
        ]);

        $element->add('type', ChoiceType::class, [
            'choices' => $options[self::OPTION_TYPES],
            'label' => false,
            'translation_domain' => $options['translation_domain'],
        ]);

        $element->add('value', TextType::class, [
            'attr' => [
                'placeholder' => 'array.placeholder.value',
            ],
            'label' => false,
            'translation_domain' => $options['translation_domain'],
        ]);

        $element->add('remove', ButtonType::class, [
            'label' => 'array.label.remove',
            'translation_domain' => $options['translation_domain'],
        ]);
    }

    /**
     * Set form elements.
     *
     * @param FormInterface $form    Form.
     * @param array         $options Form options.
     * @param mixed         $data    Form data.
     *
     * @return void
     */
    private function setForm(
        FormInterface $form,
        array $options,
        array $data = [],
    ): void {
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        foreach ($data as $n => $item) {
            $this->addElement($form, $n, $options, $item);
        }

        $form->add('add', ButtonType::class, [
            'label' => 'array.label.add',
            'translation_domain' => $options['translation_domain'],
        ]);
        $this->addElement($form, 'model', $options);
    }

}