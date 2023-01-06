<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Entity with string uid admin trait.
 */
trait UidEntityAdminTrait
{

    /**
     * Add uid field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return UidEntityAdminTrait
     */
    private function addUidField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        $mapper->add('uid');

        return $this;
    }
}