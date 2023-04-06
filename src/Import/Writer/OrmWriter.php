<?php

namespace App\Import\Writer;

use App\Import\Exception\InputNotSupportedException;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

/**
 * Import ORM writer.
 *
 * @extends AbstractWriter<object[]>
 */
class OrmWriter extends AbstractWriter
{

    /**
     * @param EntityManagerInterface $manager Doctrine object manager.
     */
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $input, array $options = []): bool
    {
        return is_array($input)
            || is_object($input);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(mixed $input, array $options): array
    {
        $entities = [];

        if (is_iterable($input)) {
            foreach ($input as $item) {
                $this->writeEntity($item, $entities);
            }
        } else {
            $this->writeEntity($input, $entities);
        }

        return $entities;
    }

    /**
     * {@inheritDoc}
     */
    protected function getUnsupportedException(
        mixed $input,
        array $options
    ): Throwable {
        return new InputNotSupportedException($this::class, $input);
    }

    /**
     * Write entities.
     *
     * @param array|object $entity      Entity or entity list.
     * @param object[]     $entities    Entity stack.
     *
     * @return void
     */
    private function writeEntity(array|object $entity, array &$entities): void
    {
        if (is_array($entity)) {
            foreach ($entity as $entry) {
                $this->writeEntity($entry, $entities);
            }
            return;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        if (!in_array($entity, $entities)) {
            $entities[] = $entity;
        }
    }

}