<?php

namespace App\Form\Budget\Type;

use App\Entity\Budget\Budget;
use App\Form\Generic\Type\EntityAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

/**
 * Third party autocomplete form type.
 */
#[AsEntityAutocompleteField]
class BudgetAutocompleteType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Budget::class,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'app_budget_autocomplete';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return EntityAutocompleteType::class;
    }

}