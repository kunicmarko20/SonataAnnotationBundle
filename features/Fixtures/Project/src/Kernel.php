<?php

namespace KunicMarko\SonataAnnotationBundle\Features\Fixtures\Project;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getProjectDir()
    {
        return __DIR__ . '/..';
    }

    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/test';
    }

    public function getLogDir()
    {
        return $this->getProjectDir().'/var/logs';
    }

    public function registerBundles()
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $loader->load($this->getProjectDir().'/config/packages/*.yaml', 'glob');
        $loader->load($this->getProjectDir().'/config/services.yaml', 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/test/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }
}
