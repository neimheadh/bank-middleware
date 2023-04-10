<?php

namespace App\Form\Generic\Type;

use App\Form\Generic\Attribute\AutocompletedEntity;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

/**
 * Auto complete entity form type.
 */
#[AsEntityAutocompleteField]
class EntityAutocompleteType extends AbstractType
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (PreSubmitEvent $event) {
                $data = $event->getData();
                $options = $event->getForm()->getConfig()->getOptions();
                $autocomplete = $data['autocomplete'] ?? null;

                if (is_string($autocomplete)) {
                    $class = new ReflectionClass($options['class']);
                    $attribute = current(
                        $class->getAttributes(AutocompletedEntity::class)
                    );

                    if ($attribute !== null) {
                        $attribute = $attribute->newInstance();
                        /** @var AutocompletedEntity $attribute */
                        $entity = $class->newInstance();
                        $accessor = PropertyAccess::createPropertyAccessor();
                        $accessor->setValue(
                            $entity,
                            $attribute->field,
                            $autocomplete
                        );
                        $this->manager->persist($entity);
                        $this->manager->flush($entity);
                        $data['autocomplete'] = $accessor->getValue(
                            $entity,
                            $attribute->primaryKey
                        );
                        $event->setData($data);
                    }
                }
            },
            1
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'data-sonata-select2' => 'false',
            ],
            'tom_select_options' => [
                'create' => true,
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'app_form_type_entity_autocomplete';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }

}