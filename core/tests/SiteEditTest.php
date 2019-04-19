<?php

use models\UserAccountModel;
use models\SiteModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteEditTest extends \BaseAppWithDBTest
{
    public function test1()
    {
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
        $this->app['timesource']->mock(2012, 1, 1, 1, 0, 0);
        $siteRepo->create($site, $user, array(), $this->getSiteQuotaUsedForTesting());
        
        $site->setTitle("TEST ME");
        $this->app['timesource']->mock(2012, 1, 1, 1, 0, 1);
        $siteRepo->edit($site, $user);
        
        
        $this->checkSiteInTest1($siteRepo->loadBySlug("test"));
    }
    
    protected function checkSiteInTest1(SiteModel $site)
    {
        $this->assertEquals("test", $site->getSlug());
        $this->assertEquals("TEST ME", $site->getTitle());
    }
}
