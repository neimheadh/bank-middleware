<?php

namespace App\Admin\User;

use App\Admin\AbstractAdmin;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\NamedEntityAdminTrait;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * User contact list admin.
 */
final class ContactListAdmin extends AbstractAdmin
{

    use DatedEntityAdminTrait;
    use NamedEntityAdminTrait;

    /**
     * {@inheritDoc}
     */
    protected function configureFields(
      FormMapper|DatagridMapper|ListMapper|ShowMapper $mapper
    ): void {
        $this->addNameField($mapper)
            ->addLifecycleDateFields($mapper);
    }

}