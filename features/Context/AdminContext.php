<?php

namespace KunicMarko\SonataAnnotationBundle\Features\Context;

use Behat\MinkExtension\Context\MinkContext;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Behat\Symfony2Extension\Context\KernelDictionary;
use KunicMarko\SonataAnnotationBundle\Features\Fixtures\CategoryFixtures;
use Doctrine\Common\DataFixtures\Loader;

class AdminContext extends MinkContext
{
    use KernelDictionary;

    /**
     * @Given I am on the dashboard
     */
    public function iAmOnTheDashboard()
    {
        $this->visitPath('/admin/dashboard');
    }

    /**
     * @Then I should see :button button
     */
    public function iShouldSeeAButton($button)
    {
        $this->getSession()->getPage()->find('xpath', "//a[contains(text(), $button)]");
    }
}
