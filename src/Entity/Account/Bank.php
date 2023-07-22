<?php

namespace App\Entity\Account;

use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Repository\Account\BankRepository;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Account bank.
 */
#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table(name: 'app_account_bank')]
#[Sonata\Admin(
    formFields: [
        'name' => new Sonata\FormField(),
    ],
    listFields: [
        'name' => new Sonata\ListField(),
    ]
)]
class Bank implements EntityInterface,
                      NamedEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
}