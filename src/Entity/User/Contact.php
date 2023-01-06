<?php

namespace App\Entity\User;

use App\Model\Entity\DatedEntityInterface;
use App\Model\Entity\DatedEntityTrait;
use App\Model\Entity\EnablingEntityInterface;
use App\Model\Entity\EnablingEntityTrait;
use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Model\Entity\User\OwnedEntityInterface;
use App\Model\Entity\User\OwnedEntityTrait;
use App\Repository\User\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User contact.
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'user_contact')]
class Contact implements EntityInterface,
                         OwnedEntityInterface,
                         EnablingEntityInterface,
                         DatedEntityInterface
{

    use EntityTrait;
    use OwnedEntityTrait;
    use EnablingEntityTrait;
    use DatedEntityTrait;

    /**
     * Contact.
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'contact_id', nullable: false, onDelete: 'CASCADE')]
    private ?User $contact = null;

    /**
     * Contact list.
     *
     * @var Collection<ContactList>
     */
    #[ORM\ManyToMany(targetEntity: ContactList::class, inversedBy: 'contacts')]
    #[ORM\JoinTable(name: 'user_contact_list_contact')]
    private Collection $lists;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
    }

    /**
     * Get user contact.
     *
     * @return User|null
     */
    public function getContact(): ?User
    {
        return $this->contact;
    }

    /**
     * Set contact.
     *
     * @param User|null $contact User contact.
     *
     * @return $this
     */
    public function setContact(?User $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Add to a contact list.
     *
     * @param ContactList $list The contact list.
     *
     * @return $this
     */
    public function addList(ContactList $list): self
    {
        if (!$this->lists->contains($list)) {
            $this->lists[] = $list;
            $list->addContact($this);
        }

        return $this;
    }

    /**
     * Get contact lists.
     *
     * @return Collection
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    /**
     * Remove from a contact list.
     *
     * @param ContactList $list The contact list.
     *
     * @return $this
     */
    public function removeList(ContactList $list): self
    {
        if ($this->lists->contains($list)) {
            $this->lists->removeElement($list);
            $list->removeContact($this);
        }

        return $this;
    }
}