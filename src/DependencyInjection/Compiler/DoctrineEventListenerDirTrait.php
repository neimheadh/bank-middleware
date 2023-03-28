<?php

namespace App\DependencyInjection\Compiler;

use ReflectionClass;
use ReflectionException;
use SplFileObject;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

/**
 * Doctrine event listener dir using trait.
 */
trait DoctrineEventListenerDirTrait
{

    /**
     * Find classes in doctrine listener directory.
     *
     * @throws ReflectionException
     *
     * @return array<ReflectionClass>
     */
    private function findClasses(ContainerBuilder $container): array
    {
        $files = $this->findFiles($container);
        $classes = [];

        foreach ($files as $filepath) {
            $class = $this->getFileClassName($filepath);

            if ($class) {
                $classes[] = new ReflectionClass($class);
            }
        }

        return $classes;
    }

    /**
     * Get doctrine event listener class files.
     *
     * @param ContainerBuilder $container Container builder.
     *
     * @return iterable
     */
    private function findFiles(ContainerBuilder $container): iterable
    {
        $dir = $container->getParameter('app.event.listener.doctrine.dir');
        $dir = $container->getParameterBag()->resolveValue($dir);

        return Finder::create()
            ->in($dir)
            ->files()
            ->name('*.php')
            ->getIterator();
    }

    /**
     * Get given file class name.
     *
     * @param string $filepath Class file.
     *
     * @return string|null
     */
    private function getFileClassName(string $filepath): ?string
    {
        $file = new SplFileObject($filepath);

        while (!$file->eof()) {
            $line = trim($file->fgets());

            if (preg_match(
                '/^class\s*([a-zA-Z][a-zA-Z0-9]*)/',
                $line,
                $match
            )) {
                return sprintf(
                    '%s\\%s',
                    $this->getFileNamespace($filepath),
                    $match[1]
                );
            }
        }

        return null;
    }

    /**
     * Get class file namespace.
     *
     * @param string $filepath Class file.
     *
     * @return string|null
     */
    private function getFileNamespace(string $filepath): ?string
    {
        $file = new SplFileObject($filepath);

        while (!$file->eof()) {
            $line = trim($file->fgets());

            if (preg_match('/^namespace\s+([^;]*);/', $line, $match)) {
                return trim($match[1]);
            }

            if (preg_match('/^class|interface|trait/', $line)) {
                break;
            }
        }

        return null;
    }

}