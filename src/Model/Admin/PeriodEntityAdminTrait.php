<?php

namespace App\Model\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;

/**
 * Entity with period admin trait.
 */
trait PeriodEntityAdminTrait
{

    /**
     * Add start date field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addEndDateField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        if ($mapper instanceof FormMapper) {
            $mapper->add('endAt', DatePickerType::class);
        } else {
            $mapper->add('endAt');
        }
        return $this;
    }

    /**
     * Add period fields.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addPeriodFields(
      DatagridMapper|FormMapper|ListMapper|ShowMapper
      $mapper
    ): self {
        return $this->addStartDateField($mapper)
            ->addEndDateField($mapper);
    }

    /**
     * Add end date field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     *
     * @return $this
     */
    private function addStartDateField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        if ($mapper instanceof FormMapper) {
            $mapper->add('startAt', DatePickerType::class);
        } else {
            $mapper->add('startAt');
        }
        return $this;
    }
}