<?php

namespace App\Entity\Account;

use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Generic\NamedEntityInterface;
use App\Model\Entity\Generic\NamedEntityTrait;
use App\Repository\Account\BankRepository;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Account bank.
 *
 * @Sonata\Admin()
 */
#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table(name: 'app_account_bank')]
class Bank implements EntityInterface,
                      NamedEntityInterface
{
    use EntityTrait;
    use NamedEntityTrait;
}