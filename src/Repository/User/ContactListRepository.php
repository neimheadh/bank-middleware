<?php

namespace App\Repository\User;

use App\Entity\User\ContactList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Contact list entity repository.
 */
class ContactListRepository extends ServiceEntityRepository
{

    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactList::class);
    }

}