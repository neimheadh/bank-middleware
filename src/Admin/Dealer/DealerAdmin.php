<?php

namespace App\Admin\Dealer;

use App\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Dealer admin configurator.
 */
class DealerAdmin extends AbstractAdmin
{

    /**
     * {@inheritDoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
          ->add('name')
          ->add('createdAt')
          ->add('updatedAt');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
          ->add('name')
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
          ->add('name');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
          ->add('id')
          ->add('name')
          ->add('createdAt')
          ->add('updatedAt');
    }

}