<?php

namespace App\Import\Writer;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Result\ResultCollection;
use App\Import\Result\ResultInterface;
use Countable;
use Doctrine\Persistence\ObjectManager;
use Iterator;

/**
 * Import ORM writer.
 */
class OrmWriter extends AbstractWriter
{

    /**
     * @param ObjectManager $manager Doctrine object manager.
     */
    public function __construct(
        private ObjectManager $manager
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): bool {
        return $input instanceof Iterator
            && $input instanceof Countable;
    }

    /**
     * {@inheritDoc}
     *
     * @param Countable|Iterator $input Writer input.
     */
    public function execute(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface {
        return new ResultCollection(
            current: fn() => $this->writeEntry($input->current()),
            count: fn() => $input->count(),
            next: fn() => $input->next(),
            key: fn() => $input->key(),
            valid: fn() => $input->valid(),
            rewind: fn() => $input->rewind(),
        );
    }

    /**
     * Write a result entry.
     *
     * @param array|object $entry The result entry.
     *
     * @return array|object
     */
    private function writeEntry(array|object $entry): array|object
    {
        if (is_object($entry)) {
            $this->manager->persist($entry);
            $this->manager->flush();

            return $entry;
        }

        return array_map(fn ($entry) => $this->writeEntry($entry), $entry);
    }
}