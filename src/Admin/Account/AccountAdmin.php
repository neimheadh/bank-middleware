<?php

declare(strict_types=1);

namespace App\Admin\Account;

use App\Admin\AbstractAdmin;
use App\Model\Admin\BalancedEntityAdminTrait;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\NamedEntityAdminTrait;
use App\Model\Admin\User\OwnedEntityAdminTrait;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Account admin configurator.
 */
final class AccountAdmin extends AbstractAdmin
{

    use BalancedEntityAdminTrait;
    use DatedEntityAdminTrait;
    use NamedEntityAdminTrait;
    use OwnedEntityAdminTrait;

    /**
     * {@inheritDoc}
     */
    protected function configureFields(
      FormMapper|DatagridMapper|ListMapper|ShowMapper $mapper
    ): void {
        $this->addNameField($mapper)
          ->addBalanceField($mapper)
          ->addOwnerField($mapper)
          ->addLifecycleDateFields($mapper);
    }

}
