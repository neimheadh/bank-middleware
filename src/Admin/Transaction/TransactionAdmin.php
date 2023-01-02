<?php

declare(strict_types=1);

namespace App\Admin\Transaction;

use App\Admin\AbstractAdmin;
use App\Entity\Transaction\Transaction;
use App\Model\Security\RoleEnum;
use DateTime;
use JsonException;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;

/**
 * Transaction admin configurator.
 */
final class TransactionAdmin extends AbstractAdmin
{

    /**
     * {@inheritDoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
          ->add('recordDate')
          ->add('transactionDate')
          ->add('name')
          ->add('balance')
          ->add('uid');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
          ->add('recordDate')
          ->add('transactionDate')
          ->add('account');

        try {
            if ($this->isGranted(RoleEnum::ROLE_SUPER_ADMIN->value)) {
                $list->add('owner');
            }
        } catch (JsonException) {}

        $list->add('dealer')
          ->add('name')
          ->add('balance')
          ->add('uid')
          ->add('createdAt')
          ->add('updatedAt')
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
          ->add('recordDate', DatePickerType::class)
          ->add('transactionDate', DatePickerType::class, [
            'required' => false,
          ])
          ->add('account')
          ->add('budget')
          ->add('name')
          ->add('balance')
          ->add('dealer')
          ->add('uid');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
          ->add('recordDate')
          ->add('transactionDate')
          ->add('id')
          ->add('name')
          ->add('balance')
          ->add('uid')
          ->add('currency');
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
