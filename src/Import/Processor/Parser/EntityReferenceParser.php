<?php

namespace App\Import\Processor\Parser;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Parser retrieving a referenced entity.
 *
 * @extends AbstractParser<object|null>
 */
final class EntityReferenceParser extends AbstractParser
{

    /**
     * Filtered entity fields.
     *
     * @var string[]
     */
    public readonly array $fields;

    /**
     * @param EntityManagerInterface $manager   Entity manager.
     * @param string                 $class     Entity class.
     * @param string                 ...$fields Filtered entity fields.
     */
    public function __construct(
        private readonly EntityManagerInterface $manager,
        public readonly string $class,
        string ...$fields,
    ) {
        $this->fields = $fields;
    }

    /**
     * {@inheritDoc}
     */
    protected function parseValue(mixed $value): ?object
    {
        $repository = $this->manager->getRepository($this->class);
        $value = is_array($value) ? $value : [$value];

        return $repository->findOneBy(
            array_combine($this->fields, $value)
        );
    }

}