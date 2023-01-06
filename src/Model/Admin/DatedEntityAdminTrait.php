<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Dated entities admin trait.
 */
trait DatedEntityAdminTrait
{

    /**
     * Add created at field to mapper.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addCreatedAtField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        if (!$mapper instanceof FormMapper) {
            $mapper->add('createdAt');
        }

        return $this;
    }

    /**
     * Add lifecycle date fields.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addLifecycleDateFields(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        return $this->addCreatedAtField($mapper)
          ->addUpdatedAtField($mapper);
    }

    /**
     * Add updated at field to mapper.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addUpdatedAtField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        if (!$mapper instanceof FormMapper) {
            $mapper->add('updatedAt');
        }

        return $this;
    }

}