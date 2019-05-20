<?php

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\EventRepository;
use repositories\GroupRepository;
use repositories\UserWatchesGroupRepository;
use repositories\builders\GroupRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupDuplicateTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $this->app['timesource']->mock(2014, 1, 1, 0, 0, 0);

        $user1 = new UserAccountModel();
        $user1->setEmail("test@jarofgreen.co.uk");
        $user1->setUsername("test");
        $user1->setPassword("password");

        $user2 = new UserAccountModel();
        $user2->setEmail("test2@jarofgreen.co.uk");
        $user2->setUsername("test2");
        $user2->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user1);
        $userRepo->create($user2);

        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");
        
        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $user1, array(), $this->getSiteQuotaUsedForTesting());
        
        $group1 = new GroupModel();
        $group1->setTitle("test1");
        $group1->setDescription("test test");
        $group1->setUrl("http://www.group.com");

        $group2 = new GroupModel();
        $group2->setTitle("test this looks similar");
        $group2->setDescription("test test");
        $group2->setUrl("http://www.group.com");

        $groupRepo = new GroupRepository($this->app);

        $this->app['timesource']->mock(2014, 1, 1, 1, 0, 0);
        $groupRepo->create($group1, $site, $user1);
        $groupRepo->create($group2, $site, $user2);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user1, $group2);

        $uwgr = new UserWatchesGroupRepository($this->app);


        // Test before

        $erb = new \repositories\builders\EventRepositoryBuilder($this->app);
        $erb->setGroup($group1);
        $this->assertEquals(0, count($erb->fetchAll()));

        $this->assertNull($uwgr->loadByUserAndGroup($user2, $group1));

        $group2 = $groupRepo->loadById($group2->getId());
        $this->assertFalse($group2->getIsDeleted());
        $this->assertNull($group2->getIsDuplicateOfId());


        // Mark
        $this->app['timesource']->mock(2014, 1, 1, 2, 0, 0);
        $groupRepo->markDuplicate($group2, $group1, $user1);


        // Test Duplicate

        $erb = new \repositories\builders\EventRepositoryBuilder($this->app);
        $erb->setGroup($group1);
        $this->assertEquals(1, count($erb->fetchAll()));

        $uwg = $uwgr->loadByUserAndGroup($user2, $group1);
        $this->assertNotNull($uwg);

        $group2 = $groupRepo->loadById($group2->getId());
        $this->assertTrue($group2->getIsDeleted());
        $this->assertEquals($group1->getId(), $group2->getIsDuplicateOfId());
    }
}
