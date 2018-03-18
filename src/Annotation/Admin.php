<?php

namespace KunicMarko\SonataAnnotationBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class Admin
{
    public $label;
    public $managerType = 'orm';
    public $group;
    public $showInDashboard;
    public $onTop;
    public $icon;
    public $labelTranslatorStrategy;
    public $labelCatalogue;
    public $controller;
    public $serviceId;

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
