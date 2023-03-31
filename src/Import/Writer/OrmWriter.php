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
     * Bulk persist option.
     */
    public const OPTION_BULK_PERSIST = 'bulk-persist';

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
        $bulkPersist = $options[self::OPTION_BULK_PERSIST] ?? false;
        $entities = [];

        if (is_iterable($input)) {
            foreach ($input as $item) {
                $this->writeEntity($item, $bulkPersist, $entities);
            }
        } else {
            $this->writeEntity($input, $bulkPersist, $entities);
        }

        if ($bulkPersist) {
            $this->manager->flush();
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
     * @param bool         $bulkPersist True if bulk persist.
     * @param object[]     $entities    Entity stack.
     *
     * @return void
     */
    private function writeEntity(
        array|object $entity,
        bool $bulkPersist,
        array &$entities,
    ): void {
        if (is_array($entity)) {
            foreach ($entity as $entry) {
                $this->writeEntity($entry, $bulkPersist, $entities);
            }
            return;
        }

        $this->manager->persist($entity);
        !$bulkPersist && $this->manager->flush();

        if (!in_array($entity, $entities)) {
            $entities[] = $entity;
        }
    }

}