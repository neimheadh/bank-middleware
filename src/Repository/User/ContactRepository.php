<?php

namespace App\Repository\User;

use App\Entity\User\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * User contact repository.
 */
class ContactRepository extends ServiceEntityRepository
{

    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

}