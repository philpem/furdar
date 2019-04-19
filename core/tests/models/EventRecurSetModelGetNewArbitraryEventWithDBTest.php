<?php

namespace tests\models;

use models\EventModel;
use models\EventRecurSetModel;
use models\SiteModel;
use models\UserAccountModel;
use repositories\EventRecurSetRepository;
use repositories\EventRepository;
use repositories\SiteRepository;
use repositories\UserAccountRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventRecurSetModelGetNewArbitraryEventWithDBTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $this->app['timesource']->mock(2015, 5, 1, 7, 0, 0);

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

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setTimezone('Europe/London');
        $start = new \DateTime("", new \DateTimeZone('Europe/London'));
        $start->setDate(2015, 5, 10);
        $start->setTime(19, 0, 0);
        $event->setStartAt($start);
        $end = new \DateTime("", new \DateTimeZone('Europe/London'));
        $end->setDate(2015, 5, 10);
        $end->setTime(21, 0, 0);
        $event->setEndAt($end);
        $event->setUrl("http://www.info.com");
        $event->setTicketUrl("http://www.tickets.com");

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);

        $event = $eventRepository->loadBySlug($site, $event->getSlug());


        $eventRecurSetRepository = new EventRecurSetRepository($this->app);
        $eventRecurSet = $eventRecurSetRepository->getForEvent($event);
        $eventRecurSet->setTimeZoneName($event->getTimezone());

        $newEvent = $eventRecurSet->getNewEventOnArbitraryDate($event, 2015, 6, 1);

        // What we are really testing here is start and end times set correctly
        $this->assertEquals("2015-06-01T18:00:00+00:00", $newEvent->getStartAtInUTC()->format("c"));
        $this->assertEquals("2015-06-01T20:00:00+00:00", $newEvent->getEndAtInUTC()->format("c"));

        $this->assertEquals("2015-06-01T19:00:00+01:00", $newEvent->getStartAtInTimezone()->format("c"));
        $this->assertEquals("2015-06-01T21:00:00+01:00", $newEvent->getEndAtInTimezone()->format("c"));
    }


    public function testAcrossBST1()
    {
        $this->app['timesource']->mock(2015, 5, 1, 7, 0, 0);

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

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setTimezone('Europe/London');
        $start = new \DateTime("", new \DateTimeZone('Europe/London'));
        $start->setDate(2015, 5, 10);
        $start->setTime(19, 0, 0);
        $event->setStartAt($start);
        $end = new \DateTime("", new \DateTimeZone('Europe/London'));
        $end->setDate(2015, 5, 10);
        $end->setTime(21, 0, 0);
        $event->setEndAt($end);
        $event->setUrl("http://www.info.com");
        $event->setTicketUrl("http://www.tickets.com");

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);

        $event = $eventRepository->loadBySlug($site, $event->getSlug());


        $eventRecurSetRepository = new EventRecurSetRepository($this->app);
        $eventRecurSet = $eventRecurSetRepository->getForEvent($event);
        $eventRecurSet->setTimeZoneName($event->getTimezone());


        $newEvent = $eventRecurSet->getNewEventOnArbitraryDate($event, 2015, 11, 1);

        // What we are really testing here is start and end times set correctly
        $this->assertEquals("2015-11-01T19:00:00+00:00", $newEvent->getStartAtInUTC()->format("c"));
        $this->assertEquals("2015-11-01T21:00:00+00:00", $newEvent->getEndAtInUTC()->format("c"));

        $this->assertEquals("2015-11-01T19:00:00+00:00", $newEvent->getStartAtInTimezone()->format("c"));
        $this->assertEquals("2015-11-01T21:00:00+00:00", $newEvent->getEndAtInTimezone()->format("c"));
    }
}
