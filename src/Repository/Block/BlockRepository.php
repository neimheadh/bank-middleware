<?php

namespace App\Repository\Block;

use App\Entity\Block\Block;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Block repository.
 *
 * @method Block[] findByType(string $type)
 */
class BlockRepository extends ServiceEntityRepository
{

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Block::class);
    }

    /**
     * Find by type and position.
     *
     * @param int $type     Block type.
     * @param int $position Block position.
     *
     * @return array<Block>
     */
    public function findByTypeAndPosition(int $type, int $position): array
    {
        return $this->findBy(['type' => $type, 'position' => $position]);
    }

}