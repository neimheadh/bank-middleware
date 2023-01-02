<?php

namespace App\Admin\Budget;

use App\Admin\AbstractAdmin;
use App\Entity\Budget\Budget;
use App\Model\Entity\PeriodicEntityInterface;
use App\Model\Security\RoleEnum;
use DateTime;
use JsonException;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Budget admin configurator.
 */
class BudgetAdmin extends AbstractAdmin
{

    /**
     * {@inheritDoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
          ->add('name')
          ->add('createdAt')
          ->add('updatedAt');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
          ->add('name');

        try {
            if ($this->isGranted(RoleEnum::ROLE_SUPER_ADMIN->value)) {
                $list->add('owner');
            }
        } catch (JsonException) {
        }

        $list
          // @todo Display periodicity string.
          ->add('periodicity')
          ->add('startAt')
          ->add('endAt')
          ->add('amount')
          ->add('balance')
          ->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
              'show' => [],
              'edit' => [],
              'delete' => [],
            ],
          ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
          ->add('name')
          ->add('amount')
          ->add('currency')
          // @todo Change with a specific periodicity field type.
          ->add('periodicity', ChoiceType::class, [
            'choices' => PeriodicEntityInterface::PERIODICITY,
          ])
          ->add('startAt', DatePickerType::class, [
            'required' => false,
          ])
          ->add('endAt', DatePickerType::class, [
            'required' => false,
          ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
          ->add('id')
          ->add('name')
          ->add('createdAt')
          ->add('updatedAt');
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
        $budget->setPeriodicity(PeriodicEntityInterface::MONTHLY);

        return $budget;
    }

}