<?php

namespace App\Import\Configuration;

/**
 * Configuration with data map.
 */
interface DataMapConfigurationInterface extends ConfigurationInterface
{

    /**
     * Get configuration data map.
     *
     * @return array
     */
    public function getDataMap(): array;

}