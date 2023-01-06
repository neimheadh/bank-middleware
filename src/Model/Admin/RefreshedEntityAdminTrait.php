<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;

/**
 * Refreshed entities admin trait.
 */
trait RefreshedEntityAdminTrait
{

    /**
     * Add refreshed at field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addLastRefreshDateField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        if (!$mapper instanceof FormMapper) {
            $mapper->add('refreshedAt');
        }

        return $this;
    }
}