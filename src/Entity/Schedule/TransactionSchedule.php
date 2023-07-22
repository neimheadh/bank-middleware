<?php

namespace App\Entity\Schedule;

use App\Model\Entity\Account\Link\AccountManyToOneInterface;
use App\Model\Entity\Account\Link\AccountManyToOneTrait;
use App\Model\Entity\Budget\Link\BudgetManyToOneInterface;
use App\Model\Entity\Budget\Link\BudgetManyToOneTrait;
use App\Model\Entity\Currency\BalancedEntityInterface;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Schedule\ScheduleEntityInterface;
use App\Model\Entity\Schedule\ScheduleEntityTrait;
use App\Model\Entity\ThirdParty\Link\ThirdPartyManyToOneInterface;
use App\Model\Entity\ThirdParty\Link\ThirdPartyManyToOneTrait;
use App\Repository\Schedule\TransactionScheduleRepository;
use App\Schedule\Generator\Account\TransactionScheduleGenerator;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;

/**
 * Transaction scheduler.
 *
 * @Sonata\ListAction(name="edit")
 * @Sonata\ListAction(name="delete")
 */
#[ORM\Entity(repositoryClass: TransactionScheduleRepository::class)]
#[ORM\Table(name: 'app_schedule_transaction')]
#[Sonata\Admin(
    formFields: [
        'startAt' => new Sonata\FormField(
            type: DateTimePickerType::class
        ),
        'finishAt' => new Sonata\FormField(
            type: DateTimePickerType::class,
            options: ['required' => false]
        ),
        'interval' => new Sonata\FormField(
            type: DateIntervalType::class,
            options: [
                'with_hours' => true,
                'with_minutes' => true,
                'with_seconds' => true,
            ]
        ),
        'account' => new Sonata\FormField(),
        'thirdParty' => new Sonata\FormField(
            type: ModelAutocompleteType::class,
            options: ['property' => 'name', 'required' => false],
        ),
        'budget' => new Sonata\FormField(
            type: ModelAutocompleteType::class,
            options: ['property' => 'name', 'required' => false]
        ),
        'name' => new Sonata\FormField(),
        'balance' => new Sonata\FormField(),
        'currency' => new Sonata\FormField(),
    ],
    listFields: [
        'name' => new Sonata\ListField(),
        'startAt' => new Sonata\ListField(),
        'finishAt' => new Sonata\ListField(),
        'interval' => new Sonata\ListField(),
        'lastExecution' => new Sonata\ListField(
            type: FieldDescriptionInterface::TYPE_DATETIME
        ),
    ]
)]
class TransactionSchedule implements EntityInterface,
                                     NamedEntityInterface,
                                     AccountManyToOneInterface,
                                     BalancedEntityInterface,
                                     ScheduleEntityInterface,
                                     BudgetManyToOneInterface,
                                     ThirdPartyManyToOneInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use AccountManyToOneTrait;
    use BalancedEntityTrait;
    use ScheduleEntityTrait;
    use ThirdPartyManyToOneTrait;
    use BudgetManyToOneTrait;

    /**
     * {@inheritDoc}
     */
    public function getGeneratorClass(): ?string
    {
        return TransactionScheduleGenerator::class;
    }

}