<?php

namespace App\DependencyInjection\Compiler;

use App\Model\Event\Listener\ORM\DoctrineEntityListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePrePersistEntityListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreRemoveEntityListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreUpdateEntityListenerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Auto-register doctrine entity listeners.
 */
class DoctrineEntityListenerCompilerPass implements CompilerPassInterface
{

    use DoctrineEventListenerDirTrait;

    /**
     * Doctrine events.
     */
    private const EVENTS = [
        DoctrinePrePersistEntityListenerInterface::class => 'prePersist',
        DoctrinePreRemoveEntityListenerInterface::class => 'preRemove',
        DoctrinePreUpdateEntityListenerInterface::class => 'preUpdate',
    ];

    /**
     * {@inheritDoc}
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container)
    {
        $classes = $this->findClasses($container);

        foreach ($classes as $class) {
            if ($this->isSupported($class)) {
                $entity = call_user_func(
                    $class->getName() . '::getEntityClass'
                );

                if ($container->has($class->getName())) {
                    $definition = $container->getDefinition($class->getName());
                } else {
                    $definition = new Definition();
                    $definition->setClass($class->getName());
                    $definition->setAutoconfigured(true);
                    $definition->setAutowired(true);
                }

                foreach (self::EVENTS as $interface => $event) {
                    if ($class->implementsInterface($interface)
                        && !$this->hasTag($definition, $event, $entity)
                    ) {
                        $definition->addTag(
                            'doctrine.event.orm.entity_listener',
                            [
                                'event' => $event,
                                'entity' => $entity,
                            ]
                        );
                    }
                }

                $container->setDefinition($class->getName(), $definition);
            }
        }
    }


    /**
     * Get if the given definition have the given event and entity
     * doctrine.orm.entity_listener tag.
     *
     * @param Definition $definition The definition.
     * @param string     $event      The event name.
     * @param string     $entity     The entity class name.
     *
     * @return bool
     */
    private function hasTag(
        Definition $definition,
        string $event,
        string $entity
    ): bool {
        $tags = $definition->getTag('doctrine.orm.entity_listener');

        foreach ($tags as $tag) {
            if ($tag['event'] === $event && $tag['entity'] === $entity) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check given class is supported.
     *
     * @param ReflectionClass $class Class.
     *
     * @return bool
     */
    private function isSupported(ReflectionClass $class): bool
    {
        if (!$class->implementsInterface(
            DoctrineEntityListenerInterface::class
        )) {
            return false;
        }

        foreach (array_keys(self::EVENTS) as $interface) {
            if ($class->implementsInterface($interface)) {
                return true;
            }
        }

        return false;
    }

}