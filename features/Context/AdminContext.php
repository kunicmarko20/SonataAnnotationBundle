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
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->getPurger()->purge();
    }

    private function getPurger()
    {
        return new ORMPurger($this->getEntityManager());
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @Given I am on the dashboard
     */
    public function iAmOnTheDashboard()
    {
        $this->visitPath('/admin/dashboard');
    }

    /**
     * @Given I have items in the database
     */
    public function iHaveItemsInTheDatabase()
    {
        $loader = new Loader();
        $loader->addFixture(new CategoryFixtures());

        $executor = new ORMExecutor($this->getEntityManager(), $this->getPurger());
        $executor->execute($loader->getFixtures());
    }

    /**
     * @Then I should see :button button
     */
    public function iShouldSeeAButton($button)
    {
        $this->getSession()->getPage()->find('xpath', "//a[contains(text(), $button)]");
    }
}
