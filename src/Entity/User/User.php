<?php

namespace App\Entity\User;

use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Repository\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * Application user.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user_user')]
class User extends BaseUser implements EntityInterface
{

    /**
     * {@inheritDoc}
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected $id = null;

    /**
     * {@inheritDoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}