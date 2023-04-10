<?php

namespace App\Form\Generic\Type;

use App\Form\Generic\DataTransformer\ArrayToYamlTransformer;
use App\Form\Generic\DataTransformer\AssociativeToFlatArrayTransformer;
use App\Form\Generic\DataTransformer\StringParseTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form array type.
 *
 * @todo Create a real form array type.
 */
class ArrayType extends AbstractType
{

    /**
     * Associative to flat transformer.
     *
     * @var AssociativeToFlatArrayTransformer
     */
    private AssociativeToFlatArrayTransformer $associativeTransformer;

    /**
     * String parsing transformer.
     *
     * @var StringParseTransformer
     */
    private StringParseTransformer $stringParseTransformer;

    public function __construct()
    {
        $this->associativeTransformer = new AssociativeToFlatArrayTransformer();
        $this->stringParseTransformer = new StringParseTransformer();
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->addModelTransformer(new ArrayToYamlTransformer(10, 2));

        return;
        $builder->addModelTransformer($this->associativeTransformer)
            ->addModelTransformer($this->stringParseTransformer);

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            [$this, 'onPostSetData']
        )->addEventListener(
            FormEvents::PRE_SUBMIT,
            [$this, 'onPreSubmit']
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

        $resolver->setDefault('allow_extra_fields', true);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return '_app_form_type_array';
        return 'app_form_type_array';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return TextareaType::class;
        return FormType::class;
    }

    /**
     * Handle form pre submit event.
     *
     * @param PreSubmitEvent $event Pre submit event.
     *
     * @return void
     * @internal
     */
    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        foreach ($form as $key => $child) {
            $form->remove($key);
        }

        if ($data) {
            $data = $this->associativeTransformer->reverseTransform($data);
            $data = $this->stringParseTransformer->reverseTransform($data);
        }

        $this->buildArrayTree($form, $data);
        $form->setData($data);
    }

    /**
     * Handle form post set data event.
     *
     * @param PostSetDataEvent $event Post set data event.
     *
     * @return void
     * @internal
     */
    public function onPostSetData(PostSetDataEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData() ?: [];

        $this->buildArrayTree($form, $data);
    }

    /**
     * Build the array tree for the given form.
     *
     * @param FormInterface $form Build form.
     * @param array|null    $data Form data.
     *
     * @return void
     */
    private function buildArrayTree(
        FormInterface $form,
        ?array $data
    ): void {
        $i = 0;

        if (is_array($data)) {
            foreach ($data as $value) {
                $form->add($i, FormType::class, [
                    'label' => false,
                ]);
                $entry = $form->get($i++);

                $entry->add('key', TextType::class, [
                    'label' => false,
                    'attr' => [
                        'class' => 'text-primary',
                    ],
                ]);

                if (is_array($value)) {
                    $entry->add('value', FormType::class, [
                        'label' => false,
                    ]);
                    $this->buildArrayTree($entry->get('value'), $value);
                } else {
                    $entry->add('value', TextType::class, [
                        'label' => false,
                    ]);
                }
            }
        }
    }

}