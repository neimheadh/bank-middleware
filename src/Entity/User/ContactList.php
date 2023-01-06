<?php

namespace App\Entity\User;

use App\Model\Entity\DatedEntityInterface;
use App\Model\Entity\DatedEntityTrait;
use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Model\Entity\NamedEntityInterface;
use App\Model\Entity\NamedEntityTrait;
use App\Model\Entity\User\OwnedEntityInterface;
use App\Model\Entity\User\OwnedEntityTrait;
use App\Repository\User\ContactListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contact list.
 */
#[ORM\Entity(repositoryClass: ContactListRepository::class)]
#[ORM\Table(name: 'user_contact_list')]
class ContactList implements EntityInterface,
                             NamedEntityInterface,
                             OwnedEntityInterface,
                             DatedEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use OwnedEntityTrait;
    use DatedEntityTrait;

    /**
     * Contact list.
     *
     * @var Collection<Contact>
     */
    #[ORM\ManyToMany(
      targetEntity: Contact::class,
      mappedBy: 'lists',
    )]
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * Add a contact.
     *
     * @param Contact $contact Added contact.
     *
     * @return $this
     */
    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->addList($this);
        }

        return $this;
    }

    /**
     * Get contact list.
     *
     * @return Collection
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * Remove a contact.
     *
     * @param Contact $contact Removed contact.
     *
     * @return $this
     */
    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            $contact->removeList($this);
        }
        return $this;
    }

}