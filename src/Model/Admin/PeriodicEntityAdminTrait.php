<?php

namespace App\Model\Admin;

use App\Form\Type\PeriodicityType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Entity with periodicity admin trait.
 */
trait PeriodicEntityAdminTrait
{

    /**
     * Add periodicity field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return PeriodicEntityAdminTrait
     */
    private function addPeriodicityField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        if ($mapper instanceof FormMapper) {
            $mapper->add('periodicity', PeriodicityType::class);
        } else {
            $mapper->add('periodicity');
        }

        return $this;
    }
}