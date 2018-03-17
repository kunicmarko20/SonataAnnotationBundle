<?php

namespace KunicMarko\SonataAnnotationBundle;

use KunicMarko\SonataAnnotationBundle\DependencyInjection\Compiler\AutoRegisterPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class SonataAnnotationBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new AutoRegisterPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
