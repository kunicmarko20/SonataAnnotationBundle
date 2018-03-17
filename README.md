Sonata Annotation Bundle
============

Adds Annotations for Sonata Admin.

This bundle was greatly inspired by [IbrowsSonataAdminAnnotationBundle](https://github.com/ibrows/IbrowsSonataAdminAnnotationBundle)

[![Build Status](https://travis-ci.org/kunicmarko20/SonataAnnotationBundle.svg?branch=master)](https://travis-ci.org/kunicmarko20/SonataAnnotationBundle)
[![Coverage Status](https://coveralls.io/repos/github/kunicmarko20/SonataAnnotationBundle/badge.svg?branch=master)](https://coveralls.io/github/kunicmarko20/SonataAnnotationBundle?branch=master)

Documentation
-------------

* [Installation](#installation)
* [How to use](#how-to-use)
* [Annotations](#annotations)

## Installation

**1.**  Add dependency with composer

```
composer require kunicmarko/sonata-annotation-bundle
```

**2.** Register the bundle in your Kernel

```php
return [
    //...
    KunicMarko\SonataAnnotationBundle\SonataAnnotationBundle::class => ['all' => true],
];
```

## How to use

Instead of creating class for Admin it is enough to just add annotation
to your entity.

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *     label="Category"
 * )
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
    /**
     * @Sonata\FormField()
     * @Sonata\ListField()
     * @Sonata\ShowField()
     * @Sonata\DatagridField()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
```

Clear cache:

```
bin/console cache:clear
```

And you will see Admin appear in your admin panel.

## Annotations

Here I will show you all the annotations and all the options you can set.
All options are optional, you don't need to set anything, it is enough
just to use annotation.

### Admin

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;
use App\Controller\YourCRUDController;

/**
 * @Sonata\Admin(
 *     label="Category",
 *     managerType="orm",
 *     group="Category",
 *     showInDashboard=true,
 *     onTop=true,
 *     icon="<i class='fa fa-user'></i>",
 *     labelTranslatorStrategy="sonata.admin.label.strategy.native",
 *     labelCatalogue="App",
 *     controller=YourCRUDController::class,
 *     serviceId="app.admin.category",
 *     routes={
 *          "import"="/import"
 *     },
 *     actions={
 *          "edit"={},
 *          "delete"={},
 *          "import"={
 *              "template"="import_button.html.twig"
 *          },
 *     }
 * )
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
}
```

### FormField

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;
use App\Controller\YourCRUDController;

/**
 * @Sonata\Admin(
 *     label="Category",
 * )
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
    /**
     * @Sonata\FormField(
     *      type="",
     *      options={},
     *      fieldDescriptionOptions={}
     * )
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
```

### ShowField

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;
use App\Controller\YourCRUDController;

/**
 * @Sonata\Admin(
 *     label="Category",
 * )
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
    /**
     * @Sonata\ShowField(
     *      type="",
     *      fieldDescriptionOptions={}
     * )
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
```

### ListField

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;
use App\Controller\YourCRUDController;

/**
 * @Sonata\Admin(
 *     label="Category",
 * )
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
    /**
     * @Sonata\ListField(identifier=true) 
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Sonata\ListField(
     *      type="",
     *      fieldDescriptionOptions={},
     *      identifier=false
     * )
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
```

### DatagridField

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use KunicMarko\SonataAnnotationBundle\Annotation as Sonata;
use App\Controller\YourCRUDController;

/**
 * @Sonata\Admin(
 *     label="Category",
 * )
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
    /**
     * @Sonata\DatagridField(
     *      type="",
     *      fieldDescriptionOptions={},
     *      filterOptions={},
     *      fieldType="",
     *      fieldOptions={}
     * )
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
```
