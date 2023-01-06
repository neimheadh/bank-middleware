<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Entity with balance admin trait.
 */
trait BalancedEntityAdminTrait
{

    /**
     * Add balance field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addBalanceField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        $mapper->add('balance');

        return $this;
    }

}