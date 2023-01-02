<?php

declare(strict_types=1);

namespace App\Admin\Account;

use App\Admin\AbstractAdmin;
use App\Model\Security\RoleEnum;
use JsonException;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Account admin configurator.
 */
final class AccountAdmin extends AbstractAdmin
{

    /**
     * {@inheritDoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('balance')
            ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name');

        try {
            if ($this->isGranted(RoleEnum::ROLE_SUPER_ADMIN->value)) {
                $list->add('owner');
            }
        } catch (JsonException) {}

        $list
            ->add('balance')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('balance')
            ->add('currency')
            ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('balance')
            ;
    }
}
