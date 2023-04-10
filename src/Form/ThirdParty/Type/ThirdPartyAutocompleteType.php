<?php

namespace App\Form\ThirdParty\Type;

use App\Entity\ThirdParty\ThirdParty;
use App\Form\Generic\Type\EntityAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

/**
 * Third party autocomplete form type.
 */
#[AsEntityAutocompleteField]
class ThirdPartyAutocompleteType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => ThirdParty::class,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'app_third_party_autocomplete';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return EntityAutocompleteType::class;
    }

}