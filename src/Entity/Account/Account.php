<?php

namespace App\Entity\Account;

use App\Model\Entity\Currency\BalancedManyToOneInterface;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Generic\CodeEntityInterface;
use App\Model\Entity\Generic\CodeEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Generic\NamedEntityInterface;
use App\Model\Entity\Generic\NamedEntityTrait;
use App\Repository\Account\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Bank account.
 *
 * @Sonata\Admin()
 */
#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'app_account_account')]
class Account implements EntityInterface,
                         NamedEntityInterface,
                         BalancedManyToOneInterface,
                         CodeEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use BalancedEntityTrait;
    use CodeEntityTrait;

    public function __construct()
    {
        $this->balance = 0;
    }

    /**
     * Account code.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256, nullable: true)]
    private ?string $code = null;

    /**
     * Entity name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256, options: ['default' => ''])]
    private ?string $name = null;

}