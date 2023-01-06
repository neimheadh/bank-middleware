<?php

declare(strict_types=1);

namespace App\Admin\Transaction;

use App\Admin\AbstractAdmin;
use App\Entity\Transaction\Transaction;
use App\Model\Admin\BalancedEntityAdminTrait;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\NamedEntityAdminTrait;
use App\Model\Admin\UidEntityAdminTrait;
use DateTime;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Transaction admin configurator.
 */
final class TransactionAdmin extends AbstractAdmin
{

    use BalancedEntityAdminTrait;
    use DatedEntityAdminTrait;
    use NamedEntityAdminTrait;
    use UidEntityAdminTrait;

    /**
     * {@inheritDoc}
     */
    protected function configureFields(
      FormMapper|DatagridMapper|ListMapper|ShowMapper $mapper
    ): void {
        $mapper->add('recordDate')
          ->add('transactionDate');

        $this
          ->addUidField($mapper)
          ->addNameField($mapper);

        $mapper->add('dealer');

        $this->addBalanceField($mapper)
          ->addLifecycleDateFields($mapper);
    }

    /**
     * {@inheritDoc}
     */
    protected function createNewInstance(): object
    {
        /** @var Transaction $transaction */
        $transaction = parent::createNewInstance();

        $transaction->setRecordDate(new DateTime());

        return $transaction;
    }

}
