<?php
use models\SiteModel;
use models\UserAccountModel;
use repositories\SiteFeatureRepository;
use repositories\SiteRepository;
use repositories\UserAccountRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteFeatureRepositoryTest extends \BaseAppWithDBTest
{

    /**
     *
     */
    public function test1()
    {
        $feature = new \sitefeatures\EditCommentsFeature();

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);

        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $user, array(), $this->getSiteQuotaUsedForTesting());

        $siteFeatureRepo = new SiteFeatureRepository($this->app);


        // Test Get Default Option
        $this->app['timesource']->mock(2015, 1, 1, 1, 1, 1);

        $data = $siteFeatureRepo->getForSiteAsTree($site);
        $this->assertEquals(false, $data[$feature->getExtensionId()][$feature->getFeatureId()]->isOn());


        // Test Set True
        $this->app['timesource']->mock(2015, 1, 1, 1, 1, 2);

        $siteFeatureRepo->setFeature($site, $feature, true, $user);

        $data = $siteFeatureRepo->getForSiteAsTree($site);
        $this->assertEquals(true, $data[$feature->getExtensionId()][$feature->getFeatureId()]->isOn());


        // Test Set False
        $this->app['timesource']->mock(2015, 1, 1, 1, 1, 3);

        $siteFeatureRepo->setFeature($site, $feature, false, $user);

        $data = $siteFeatureRepo->getForSiteAsTree($site);
        $this->assertEquals(false, $data[$feature->getExtensionId()][$feature->getFeatureId()]->isOn());


        // Test Set False whilst already false
        $this->app['timesource']->mock(2015, 1, 1, 1, 1, 4);

        $siteFeatureRepo->setFeature($site, $feature, false, $user);

        $data = $siteFeatureRepo->getForSiteAsTree($site);
        $this->assertEquals(false, $data[$feature->getExtensionId()][$feature->getFeatureId()]->isOn());
    }
}
