<?php

namespace App\Form\Account\Type;

use App\Form\Budget\Type\BudgetAutocompleteType;
use App\Form\ThirdParty\Type\ThirdPartyAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Quick transaction form type.
 */
class QuickTransactionType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add(
            'balance',
            NumberType::class,
            [
                'label' => 'Account.quick_transaction.label.balance',
                'translation_domain' => $options['translation_domain'],
            ]
        )->add(
            'thirdParty',
            ThirdPartyAutocompleteType::class,
            [
                'label' => 'Account.quick_transaction.label.third_party',
                'required' => false,
                'translation_domain' => $options['translation_domain'],
            ]
        )->add(
            'budget',
            BudgetAutocompleteType::class,
            [
                'label' => 'Account.quick_transaction.label.budget',
                'required' => false,
                'translation_domain' => $options['translation_domain'],
            ]
        )->add(
            'name',
            TextType::class,
            // @todo Move form-control class into general extension.
            [
                'attr' => ['class' => 'form-control'],
                'label' => 'Account.quick_transaction.label.name',
                'required' => false,
                'translation_domain' => $options['translation_domain'],
            ],
        )->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Account.quick_transaction.label.submit',
                'translation_domain' => $options['translation_domain'],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'form',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return FormType::class;
    }

}