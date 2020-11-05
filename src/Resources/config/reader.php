<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use KunicMarko\SonataAnnotationBundle\Reader\ActionButtonReader;
use KunicMarko\SonataAnnotationBundle\Reader\AddChildReader;
use KunicMarko\SonataAnnotationBundle\Reader\DashboardActionReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridReader;
use KunicMarko\SonataAnnotationBundle\Reader\DatagridValuesReader;
use KunicMarko\SonataAnnotationBundle\Reader\ExportReader;
use KunicMarko\SonataAnnotationBundle\Reader\FormReader;
use KunicMarko\SonataAnnotationBundle\Reader\ListReader;
use KunicMarko\SonataAnnotationBundle\Reader\RouteReader;
use KunicMarko\SonataAnnotationBundle\Reader\ShowReader;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // Use "service" function for creating references to services when dropping support for Symfony 4.4
    // Use "param" function for creating references to parameters when dropping support for Symfony 5.1
    $containerConfigurator->services()

        ->set('sonata.annotation.reader.form', FormReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(FormReader::class, 'sonata.annotation.reader.form')

        ->set('sonata.annotation.reader.list', ListReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(ListReader::class, 'sonata.annotation.reader.list')

        ->set('sonata.annotation.reader.show', ShowReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(ShowReader::class, 'sonata.annotation.reader.show')

        ->set('sonata.annotation.reader.datagrid', DatagridReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(DatagridReader::class, 'sonata.annotation.reader.datagrid')

        ->set('sonata.annotation.reader.route', RouteReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(RouteReader::class, 'sonata.annotation.reader.route')

        ->set('sonata.annotation.reader.action_button', ActionButtonReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(ActionButtonReader::class, 'sonata.annotation.reader.action_button')

        ->set('sonata.annotation.reader.dashboard_action', DashboardActionReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(DashboardActionReader::class, 'sonata.annotation.reader.dashboard_action')

        ->set('sonata.annotation.reader.export', ExportReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(ExportReader::class, 'sonata.annotation.reader.export')

        ->set('sonata.annotation.reader.datagrid_values', DatagridValuesReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(DatagridValuesReader::class, 'sonata.annotation.reader.datagrid_values')

        ->set('sonata.annotation.reader.add_child', AddChildReader::class)
        ->public()
        ->args([
            new ReferenceConfigurator('annotation_reader'),
        ])

        ->alias(AddChildReader::class, 'sonata.annotation.reader.add_child')
    ;
};
