<?php

namespace App\Entity\Budget;

use App\Form\Generic\Attribute\AutocompletedEntity;
use App\Model\Entity\Currency\BalancedEntityInterface;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\StringableNamedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\StringableNamedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Budget.
 */
#[AutocompletedEntity(field: 'name')]
#[ORM\Entity]
#[ORM\Table(name: 'app_budget_budget')]
#[Sonata\Admin]
class Budget implements EntityInterface,
                        StringableNamedEntityInterface,
                        BalancedEntityInterface
{

    use EntityTrait;
    use StringableNamedEntityTrait;
    use BalancedEntityTrait;

    public function __construct()
    {
        $this->balance = 0.0;
    }

}