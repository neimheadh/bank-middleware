<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Entity with an enable switch admin trait.
 */
trait EnablingEntityAdminTrait
{

    /**
     * Add enabled field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addEnabledField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        $mapper->add('enabled');

        return $this;
    }
}