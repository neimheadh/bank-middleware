<?php

namespace App\Form\Generic\ChoiceLoader;

use Composer\Autoload\ClassLoader;
use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;

/**
 * Class list loader.
 */
class ClassListChoiceLoader implements ChoiceLoaderInterface
{

    /**
     * @param string $namespace Classes namespace.
     * @param int    $depth     How deep we should go into the namespace dir.
     *                          Negative value means no limit.
     */
    public function __construct(
        private readonly string $namespace,
        private readonly int $depth = -1
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function loadChoiceList(callable $value = null): ChoiceListInterface
    {
        $classes = $this->findClasses($this->namespace);

        return new ArrayChoiceList(array_combine($classes, $classes), $value);
    }

    /**
     * {@inheritDoc}
     */
    public function loadChoicesForValues(
        array $values,
        callable $value = null
    ): array {
        $classes = array_intersect_key(
            $values,
            $this->findClasses($this->namespace)
        );

        return (new ArrayChoiceList(
            array_combine($classes, $classes), $value
        ))->getChoices();
    }

    /**
     * {@inheritDoc}
     */
    public function loadValuesForChoices(
        array $choices,
        callable $value = null
    ): array {
        $classes = array_intersect(
            $choices,
            $this->findClasses($this->namespace)
        );

        return (new ArrayChoiceList(
            array_combine($classes, $classes), $value
        ))->getValues();
    }


    /**
     * Get namespace psr4 directory.
     *
     * @param string $namespace
     *
     * @return array<string, string>
     */
    private function getNamespacePsr4(string $namespace): array
    {
        $loader = current(ClassLoader::getRegisteredLoaders());
        $psr4Prefixes = $loader->getPrefixesPsr4();
        $psr4 = '';

        foreach ($psr4Prefixes as $ns => $dirs) {
            if (str_starts_with($namespace, $ns)
                && strlen($ns) > strlen($psr4)
            ) {
                $psr4 = $ns;
            }
        }

        if ($psr4 === '' || empty($psr4Prefixes[$psr4])) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot find PSR4 directory for namespace %s.',
                    $namespace
                )
            );
        }

        return [substr($psr4, 0, -1) => current($psr4Prefixes[$psr4])];
    }

    /**
     * Find given namespace classes.
     *
     * @param string $namespace The namespace.
     *
     * @return array
     */
    private function findClasses(string $namespace): array
    {
        $psr4 = $this->getNamespacePsr4($namespace);
        $psr4directory = current($psr4);
        $psr4Namespace = current(array_keys($psr4));

        $directory = $psr4directory . str_replace(
                '\\',
                '/',
                substr($namespace, strlen($psr4Namespace))
            );

        $finder = Finder::create()
            ->in($directory)
            ->files()
            ->name('*.php');

        if ($this->depth >= 0) {
            $finder->depth("<= $this->depth");
        }
        /** @var iterable<string, SplFileInfo> $files */
        $files = $finder->getIterator();
        $classes = [];

        foreach ($files as $file) {
            try {
                $class = new ReflectionClass(
                    str_replace(
                        [$psr4directory, '/', '.php'],
                        [$psr4Namespace, '\\', ''],
                        $file->getPathname()
                    )
                );

                if ($class->isInstantiable()) {
                    $classes[] = $class->getName();
                }
            } catch (ReflectionException) {
            }
        }

        return $classes;
    }

    /**
     * Get the PSR4 directory for the given namespace.
     *
     * @param string $namespace The namespace.
     *
     * @return iterable
     */
    private function findPsr4Directories(string $namespace): iterable
    {
        $loader = current(ClassLoader::getRegisteredLoaders());
        $psr4 = $loader->getPrefixesPsr4();
        $entitiesPsr4 = '';

        foreach ($psr4 as $ns => $dirs) {
            if (substr($namespace, 0, strlen($ns)) === $ns
                && strlen($ns) > strlen($entitiesPsr4)
            ) {
                $entitiesPsr4 = $ns;
            }
        }

        if ($entitiesPsr4 === '') {
            throw new LogicException(
                sprintf(
                    'Cannot find PS4 for namespace %s',
                    $namespace
                )
            );
        }

        return array_filter(
            array_map(
                fn(string $dir) => $dir . str_replace(
                        '\\',
                        '/',
                        substr($namespace, strlen($entitiesPsr4) - 1)
                    ),
                $psr4[$entitiesPsr4]
            ),
            fn(string $dir) => is_dir($dir)
        );
    }

}