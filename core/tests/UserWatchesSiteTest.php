<?php

use models\UserAccountModel;
use models\SiteModel;
use models\UserWatchesSiteModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\UserWatchesSiteRepository;
use repositories\builders\UserWatchesSiteRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSiteTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $userOwner = new UserAccountModel();
        $userOwner->setEmail("test2@jarofgreen.co.uk");
        $userOwner->setUsername("test2");
        $userOwner->setPassword("password");
        
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
        $userRepo->create($userOwner);
        
        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");
        
        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $userOwner, array(), $this->getSiteQuotaUsedForTesting());
        
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        
        # Part 1: User does not watch site
        $t = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $this->assertNull($t);
        
        $b = new UserWatchesSiteRepositoryBuilder($this->app);
        $t = $b->fetchAll();
        $this->assertEquals(1, count($t));
        
        # Part 2: Watches!
        $userWatchesSiteRepo->startUserWatchingSite($user, $site);
            
        $t = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $this->assertEquals($user->getId(), $t->getUserAccountId());
        $this->assertEquals(true, $t->getIsWatching());
        $this->assertEquals(true, $t->getIsWasOnceWatching());
        
        $b = new UserWatchesSiteRepositoryBuilder($this->app);
        $t = $b->fetchAll();
        $this->assertEquals(2, count($t));
        
        
        # Part 3: Stops Watching!
        $userWatchesSiteRepo->stopUserWatchingSite($user, $site);

        $t = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $this->assertEquals(false, $t->getIsWatching());
        $this->assertEquals(true, $t->getIsWasOnceWatching());
        
        $b = new UserWatchesSiteRepositoryBuilder($this->app);
        $t = $b->fetchAll();
        $this->assertEquals(1, count($t));
    }
}
