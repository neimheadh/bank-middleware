<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Named entity admin trait.
 */
trait NamedEntityAdminTrait
{

    /**
     * Add name field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     */
    private function addNameField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        $mapper->add('name');

        return $this;
    }

}