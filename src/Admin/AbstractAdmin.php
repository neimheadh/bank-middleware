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
     * Add actions to list mapper.
     *
     * @param ListMapper $list List mapper.
     *
     * @return $this
     */
    protected function addListActions(ListMapper $list): self
    {
        $list->add(ListMapper::NAME_ACTIONS, null, [
          'actions' => [
            'show' => [],
            'edit' => [],
            'delete' => [],
          ],
        ]);

        return $this;
    }

    /**
     * Configure admin datagrid filters.
     *
     * @param DatagridMapper $filter Datagrid mapper.
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $this->configureFields($filter);
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
        $this->configureFields($list);
        $this->addListActions($list);
    }

    /**
     * Configure admin fields.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return void
     */
    protected function configureFields(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): void {

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
        $this->configureFields($form);
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
        $this->configureFields($show);
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