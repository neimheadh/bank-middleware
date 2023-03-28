<?php

namespace App\DependencyInjection\Compiler;

use App\Model\Event\Listener\ORM\DoctrinePrePersistEventListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreRemoveEventListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreUpdateEventListenerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Auto-register doctrine event listeners.
 */
class DoctrineEventListenerCompilerPass implements CompilerPassInterface
{

    use DoctrineEventListenerDirTrait;

    /**
     * Doctrine events.
     */
    private const EVENTS = [
        DoctrinePrePersistEventListenerInterface::class => 'prePersist',
        DoctrinePreRemoveEventListenerInterface::class => 'preRemove',
        DoctrinePreUpdateEventListenerInterface::class => 'preUpdate',
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
                if ($container->has($class->getName())) {
                    $definition = $container->getDefinition($class->getName());
                } else {
                    $definition = new Definition();
                    $definition->setClass($class->getName());
                    $definition->setAutoconfigured(true);
                    $definition->setAutowired(true);
                }

                $definition->setClass($class->getName());
                $definition->setAutowired(true);
                $definition->setAutoconfigured(true);

                foreach (self::EVENTS as $interface => $event) {
                    if ($class->implementsInterface($interface)
                        && !$this->hasTag($definition, $event)
                    ) {
                        $definition->addTag('doctrine.event_listener', [
                            'event' => $event,
                        ]);
                    }
                }

                $container->setDefinition($class->getName(), $definition);
            }
        }
    }

    /**
     * Get if the given definition have the given event doctrine.event_listener
     * tag.
     *
     * @param Definition $definition The definition.
     * @param string     $event      The event name.
     *
     * @return bool
     */
    private function hasTag(Definition $definition, string $event): bool
    {
        $tags = $definition->getTag('doctrine.event_listener');

        foreach ($tags as $tag) {
            if ($tag['event'] === $event) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get if given class is supported.
     *
     * @param ReflectionClass $class Class.
     *
     * @return bool
     */
    private function isSupported(ReflectionClass $class): bool
    {
        foreach (array_keys(self::EVENTS) as $interface) {
            if ($class->implementsInterface($interface)) {
                return true;
            }
        }

        return false;
    }

}