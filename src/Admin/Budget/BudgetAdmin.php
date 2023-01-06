<?php

namespace App\Admin\Budget;

use App\Admin\AbstractAdmin;
use App\Entity\Budget\Budget;
use App\Form\Type\PeriodicityType;
use App\Model\Admin\AmountedEntityAdminTrait;
use App\Model\Admin\BalancedEntityAdminTrait;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\NamedEntityAdminTrait;
use App\Model\Admin\PeriodEntityAdminTrait;
use App\Model\Admin\PeriodicEntityAdminTrait;
use App\Model\Admin\RefreshedEntityAdminTrait;
use App\Model\Admin\User\OwnedEntityAdminTrait;
use DateTime;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Budget admin configurator.
 */
final class BudgetAdmin extends AbstractAdmin
{

    use AmountedEntityAdminTrait;
    use BalancedEntityAdminTrait;
    use DatedEntityAdminTrait;
    use NamedEntityAdminTrait;
    use OwnedEntityAdminTrait;
    use PeriodEntityAdminTrait;
    use PeriodicEntityAdminTrait;
    use RefreshedEntityAdminTrait;

    /**
     * {@inheritDoc}
     */
    protected function configureFields(
      FormMapper|DatagridMapper|ListMapper|ShowMapper $mapper
    ): void {
        $this->addNameField($mapper)
          ->addPeriodFields($mapper)
          ->addPeriodicityField($mapper)
          ->addOwnerField($mapper)
          ->addBalanceField($mapper)
          ->addAmountField($mapper)
          ->addLifecycleDateFields($mapper)
          ->addLastRefreshDateField($mapper);
    }

    /**
     * {@inheritDoc}
     */
    protected function createNewInstance(): object
    {
        /** @var Budget $budget */
        $budget = parent::createNewInstance();

        // Start date at first day of month by default.
        $start = new DateTime();
        $start->setDate(
          $start->format('Y'),
          $start->format('m'),
          1
        );
        $budget->setStartAt($start);

        // Periodicity at monthly by default.
        $budget->setPeriodicity('1 ' . PeriodicityType::PERIOD_MONTHLY);

        return $budget;
    }

}