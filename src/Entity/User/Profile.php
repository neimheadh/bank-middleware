<?php

namespace App\Entity\User;

use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * User profile.
 */
#[ORM\Entity]
#[ORM\Table(name: 'app_user_profile')]
class Profile implements EntityInterface
{

    use EntityTrait;

    /**
     * User personal email.
     *
     * Can be different from the user authentication email.
     *
     * @var string|null
     */
    #[ORM\Column(
        type: 'string',
        length: 256,
        nullable: true,
    )]
    private ?string $email = null;

    /**
     * User firstname.
     *
     * @var string|null
     */
    #[ORM\Column(
        type: 'string',
        length: 128,
        nullable: true,
    )]
    private ?string $firstname = null;

    /**
     * User lastname.
     *
     * @var string|null
     */
    #[ORM\Column(
        type: 'string',
        length: 128,
        nullable: true,
    )]
    private ?string $lastname = null;

    /**
     * Profile user.
     *
     * @var User|null
     */
    #[ORM\OneToOne(
        inversedBy: 'profile',
        targetEntity: User::class,
        cascade: ['persist'],
    )]
    private ?User $user = null;

    /**
     * Get user personal email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get user firstname.
     *
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Get user lastname.
     *
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Get profile user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user personal email.
     *
     * @param string|null $email User personal email.
     *
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set user firstname.
     *
     * @param string|null $firstname User firstname.
     *
     * @return $this
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Set user lastname.
     *
     * @param string|null $lastname User lastname.
     *
     * @return $this
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Set profile user.
     *
     * @param User|null $user Profile user.
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        if ($this->user?->getProfile() == $this) {
            $this->user->setProfile(null);
        }

        $this->user = $user;

        if ($user->getProfile() !== $this) {
            $user->setProfile($this);
        }

        return $this;
    }

}