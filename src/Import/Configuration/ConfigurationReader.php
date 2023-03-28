<?php

namespace App\Import\Configuration;

use App\Import\Exception\Configuration\ConfigurationFileParsingException;
use SplFileObject;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Configuration reader.
 */
final class ConfigurationReader
{

    /**
     * @param ContainerInterface $container Symfony container.
     */
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * Read configuration from a Yaml string.
     *
     * @param string $yaml Yaml string.
     *
     * @return ConfigurationInterface
     */
    public function readYaml(string $yaml): ConfigurationInterface
    {
        /** @var ConfigurationInterface $config */
        $config = $this->parseObject(Yaml::parse($yaml));

        return $config;
    }

    /**
     * Read configuration from a Yaml file.
     *
     * @param string|SplFileObject $file Yaml configuration file.
     *
     * @return ConfigurationInterface
     */
    public function readYamlFile(string|SplFileObject $file
    ): ConfigurationInterface {
        $file = $this->getFile($file);
        /** @var ConfigurationInterface $config */
        $config = $this->parseObject(Yaml::parseFile($file->getPathname()), 0);

        return $config;
    }

    /**
     * Get file object.
     *
     * @param string|SplFileObject $file Input file.
     *
     * @return SplFileObject
     */
    private function getFile(string|SplFileObject $file): SplFileObject
    {
        if (is_string($file)) {
            $file = new SplFileObject($file, 'r');
        }

        return $file;
    }

    /**
     * Parse a list of arguments.
     *
     * @param array $data Arguments data.
     *
     * @return array
     */
    private function parseArguments(array $data): array
    {
        return array_map(
            function (mixed $entry) {
                if (is_array($entry)) {
                    return isset($entry['_class'])
                        ? $this->parseObject($entry)
                        : $this->parseArguments($entry);
                }

                if (is_string($entry) && str_starts_with($entry, '@')) {
                    $entry = $this->container->get(substr($entry, 1));
                }

                return $entry;
            },
            $data
        );
    }

    /**
     * Parse a configuration object.
     *
     * @param array $data Configuration data.
     *
     * @return object
     */
    private function parseObject(array $data): object
    {
        if (!isset($data['_class'])) {
            throw new ConfigurationFileParsingException(
                'Missing "_class" configuration attribute.',
                ConfigurationFileParsingException::OBJECT_NO_CLASS_ATTRIBUTE
            );
        }

        $class = $data['_class'];
        if (!class_exists($class)) {
            throw new ConfigurationFileParsingException(
                sprintf('Class "%s" does not exists.', $class),
                ConfigurationFileParsingException::OBJECT_CLASS_NOT_FOUND
            );
        }

        $arguments = $data['_arguments'] ?? [];
        if (!is_array($arguments)) {
            $arguments = [$arguments];
        }
        $arguments = $this->parseArguments($arguments);

        return new $class(...$arguments);
    }

}