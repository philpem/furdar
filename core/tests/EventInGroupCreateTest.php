<?php

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\EventRepository;
use repositories\builders\GroupRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventInGroupCreateTest extends \BaseAppWithDBTest
{
    public function mktime($year=2012, $month=1, $day=1, $hour=0, $minute=0, $second=0)
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        $dt->setTime($hour, $minute, $second);
        $dt->setDate($year, $month, $day);
        return $dt;
    }
    
    public function testMultiple()
    {
        $this->app['timesource']->mock(2013, 7, 1, 7, 0, 0);
        
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
        
        $group1 = new GroupModel();
        $group1->setTitle("test");
        $group1->setDescription("test test");
        $group1->setUrl("http://www.group.com");
        
        $group2 = new GroupModel();
        $group2->setTitle("cat");
        $group2->setDescription("cat cat");
        $group2->setUrl("http://www.cat.com");
        
        
        $groupRepo = new GroupRepository($this->app);
        $groupRepo->create($group1, $site, $user);
        $groupRepo->create($group2, $site, $user);
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user, $group1, array($group1, $group2));

        // Check groups
        $groupRB = new GroupRepositoryBuilder($this->app);
        $groupRB->setEvent($event);
        $groups = $groupRB->fetchAll();
        $this->assertEquals(2, count($groups));
    }
}
