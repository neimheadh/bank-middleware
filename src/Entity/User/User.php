<?php

namespace App\Entity\User;

use App\Repository\User\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * Authentication user.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user_user')]
class User extends BaseUser
{

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    protected $id = null;

    /**
     * User profile.
     *
     * @var Profile|null
     */
    #[ORM\OneToOne(
        inversedBy: 'user',
        targetEntity: Profile::class,
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    #[ORM\JoinColumn(
        nullable: false,
    )]
    private ?Profile $profile = null;

    public function __construct()
    {
        $this->profile = new Profile();
        $this->profile->setUser($this);
    }

    /**
     * Get user profile.
     *
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * Set user profile.
     *
     * @param Profile|null $profile User profile.
     *
     * @return $this
     */
    public function setProfile(?Profile $profile): self
    {
        if ($this->profile?->getUser() === $this) {
            $this->profile->setUser(null);
        }

        $this->profile = $profile;

        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        return $this;
    }

}
