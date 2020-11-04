<?php

namespace KunicMarko\SonataAnnotationBundle\Features\Fixtures;

use KunicMarko\SonataAnnotationBundle\Features\Fixtures\Project\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category('Dummy Category');

        $manager->persist($category);
        $manager->flush();
    }
}
