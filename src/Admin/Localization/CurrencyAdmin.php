<?php

declare(strict_types=1);

namespace App\Admin\Localization;

use App\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Currency admin configurator.
 */
final class CurrencyAdmin extends AbstractAdmin
{

    /**
     * {@inheritDoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('char')
            ->add('name')
            ->add('iso')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('char')
            ->add('name')
            ->add('iso')
            ->add('createdAt')
            ->add('updatedAt')
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
            ->add('char')
            ->add('name')
            ->add('iso')
            ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('char')
            ->add('name')
            ->add('iso')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }
}
