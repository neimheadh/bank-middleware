<?php

namespace App\Admin\User;

use App\Admin\AbstractAdmin;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\User\OwnedEntityAdminTrait;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * User contact admin.
 */
final class ContactAdmin extends AbstractAdmin
{
    use DatedEntityAdminTrait;
    use OwnedEntityAdminTrait;

    /**
     * {@inheritDoc}
     */
    protected function configureFields(
      FormMapper|DatagridMapper|ListMapper|ShowMapper $mapper
    ): void {
        $mapper->add('contact');

        $this
          ->addOwnerField($mapper)
          ->addLifecycleDateFields($mapper);
    }

}