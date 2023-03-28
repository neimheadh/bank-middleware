<?php

namespace App;

use App\DependencyInjection\Compiler\DoctrineEntityListenerCompilerPass;
use App\DependencyInjection\Compiler\DoctrineEventListenerCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

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

    /**
     * {@inheritDoc}
     */
    protected function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(
            new DoctrineEntityListenerCompilerPass(),
            priority: 1
        );
        $container->addCompilerPass(
            new DoctrineEventListenerCompilerPass(),
            priority: 1
        );
    }

}
