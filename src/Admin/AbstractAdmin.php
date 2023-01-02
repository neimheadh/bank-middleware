<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as BaseAbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Sonata abstract admin class wrapper.
 *
 * Add admin functions php documentation.
 */
class AbstractAdmin extends BaseAbstractAdmin
{

    /**
     * Configure admin datagrid filters.
     *
     * @param DatagridMapper $filter Datagrid mapper.
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        parent::configureDatagridFilters(
          $filter
        );
    }

    /**
     * Configure admin list fields.
     *
     * @param ListMapper $list List mapper.
     *
     * @return void
     */
    protected function configureListFields(ListMapper $list): void
    {
        parent::configureListFields(
          $list
        );
    }

    /**
     * Configure admin form fields.
     *
     * @param FormMapper $form Form mapper.
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $form): void
    {
        parent::configureFormFields(
          $form
        );
    }

    /**
     * Configure admin show fields.
     *
     * @param ShowMapper $show Show mapper.
     *
     * @return void
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        parent::configureShowFields(
          $show
        );
    }

    /**
     * Create entity new instance.
     *
     * @return object
     */
    protected function createNewInstance(): object
    {
        return parent::createNewInstance();
    }

}