<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Application kernel.
 */
class Kernel extends BaseKernel
{

    use MicroKernelTrait;

    /**
     * Configure the container.
     *
     * @param ContainerConfigurator $container Container configurator.
     *
     * @return void
     */
    private function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();

        $container->import($configDir . '/{packages}/*.{php,yaml}');
        $container->import(
            $configDir . '/{packages}/' . $this->environment . '/*.{php,yaml}'
        );

        $container->import($configDir . '/{services}/*.{php,yaml}');
        if (is_dir($configDir . '/services/' . $this->environment)) {
            $container->import(
                $configDir . '/{services}/' . $this->environment . '/*.{php,yaml}'
            );
        }
    }

}
