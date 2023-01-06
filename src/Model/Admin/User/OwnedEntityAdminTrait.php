<?php

namespace App\Model\Admin\User;

use App\Model\Security\RoleEnum;
use JsonException;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Owned entities admin trait.
 */
trait OwnedEntityAdminTrait
{

    /**
     * Add owner field.
     *
     * @param DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper Mapper.
     */
    private function addOwnerField(
      DatagridMapper|FormMapper|ListMapper|ShowMapper $mapper
    ): self {
        $this->canAccessOwner() && $mapper->add('owner');

        return $this;
    }

    /**
     * Check if current user can access owner.
     *
     * @return bool
     */
    private function canAccessOwner(): bool
    {
        try {
            return $this->isGranted(RoleEnum::ROLE_SUPER_ADMIN->value);
        } catch (JsonException) {
        }

        return false;
    }

}