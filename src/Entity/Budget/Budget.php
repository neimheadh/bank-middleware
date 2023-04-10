<?php

namespace App\Entity\Budget;

use App\Form\Generic\Attribute\AutocompletedEntity;
use App\Model\Entity\Currency\BalancedEntityInterface;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Generic\NamedEntityInterface;
use App\Model\Entity\Generic\NamedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;
use Doctrine\ORM\Mapping as ORM;

/**
 * Budget.
 */
#[AutocompletedEntity(field: 'name')]
#[ORM\Entity]
#[ORM\Table(name: 'budget_budget')]
#[Sonata\Admin]
class Budget implements EntityInterface,
                        NamedEntityInterface,
                        BalancedEntityInterface
{
    use EntityTrait;
    use NamedEntityTrait;
    use BalancedEntityTrait;

    public function __construct()
    {
        $this->balance = 0.0;
    }

}