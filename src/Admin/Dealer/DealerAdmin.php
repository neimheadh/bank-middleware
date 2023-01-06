<?php

namespace App\Admin\Dealer;

use App\Admin\AbstractAdmin;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\NamedEntityAdminTrait;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Dealer admin configurator.
 */
final class DealerAdmin extends AbstractAdmin
{

    use NamedEntityAdminTrait;
    use DatedEntityAdminTrait;

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