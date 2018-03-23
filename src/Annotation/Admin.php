<?php

declare(strict_types=1);

namespace KunicMarko\SonataAnnotationBundle\Annotation;

use KunicMarko\SonataAnnotationBundle\Admin\AnnotationAdmin;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Admin implements AnnotationInterface
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $managerType = 'orm';

    /**
     * @var string
     */
    public $group;

    /**
     * @var bool
     */
    public $showInDashboard;

    /**
     * @var bool
     */
    public $onTop;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $labelTranslatorStrategy;

    /**
     * @var string
     */
    public $labelCatalogue;

    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $serviceId;

    /**
     * @var string
     */
    public $admin = AnnotationAdmin::class;

    /**
     * @var string
     */
    public $code;

    public function getTagOptions(): array
    {
        return [
            'manager_type'              => $this->managerType,
            'group'                     => $this->group,
            'label'                     => $this->label,
            'show_in_dashboard'         => $this->showInDashboard,
            'on_top'                    => $this->onTop,
            'icon'                      => $this->icon,
            'label_translator_strategy' => $this->labelTranslatorStrategy,
            'label_catalogue'           => $this->labelCatalogue,
        ];
    }
}
