<?php

use models\UserAccountModel;
use models\SiteModel;
use models\EventModel;
use org\openacalendar\curatedlists\models\CuratedListModel;
use repositories\SiteRepository;
use repositories\EventRepository;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListEventTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        TimeSource::mock(2014, 5, 1, 7, 0, 0);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        // We are deliberately using the UserAccountRepository from this extension so we have tests to cover instantiating and using this class
        // It extends the core one so has all methods.
        $userRepo = new \org\openacalendar\curatedlists\repositories\UserAccountRepository($this->app);
        $userRepo->create($user);

        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");
        
        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $user, array(), $this->getSiteQuotaUsedForTesting());
        
        $curatedList = new CuratedListModel();
        $curatedList->setTitle("test");
        $curatedList->setDescription("test this!");
        
        $clRepo = new CuratedListRepository();
        $clRepo->create($curatedList, $site, $user);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);


        // Test Before
        $eventRepositoryBuilder = new \repositories\builders\EventRepositoryBuilder($this->app);
        $eventRepositoryBuilder->setCuratedList($curatedList);
        $this->assertEquals(0, count($eventRepositoryBuilder->fetchAll()));

        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setEventInformation($event);
        $curatedListsWithInfo = $curatedListRepoBuilder->fetchAll();
        $this->assertEquals(1, count($curatedListsWithInfo));
        $curatedListWithInfo = $curatedListsWithInfo[0];
        $this->assertEquals(false, $curatedListWithInfo->getIsEventInlist());


        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setContainsEvent($event);
        $curatedListsContainsEvent = $curatedListRepoBuilder->fetchAll();
        $this->assertEquals(0, count($curatedListsContainsEvent));


        // Add event to list
        TimeSource::mock(2014, 5, 1, 8, 0, 0);
        $clRepo->addEventtoCuratedList($event, $curatedList, $user);


        // Test After

        // ... we don't ask for extra info
        $eventRepositoryBuilder = new \repositories\builders\EventRepositoryBuilder($this->app);
        $eventRepositoryBuilder->setCuratedList($curatedList);
        $events = $eventRepositoryBuilder->fetchAll();
        $this->assertEquals(1, count($events));
        $eventWithInfo = $events[0];
        $this->assertNull($eventWithInfo->getInCuratedListGroupId());
        $this->assertNull($eventWithInfo->getInCuratedListGroupSlug());
        $this->assertNull($eventWithInfo->getInCuratedListGroupTitle());
        $this->assertFalse($eventWithInfo->getIsEventInCuratedList());

        // ... we DO ask for extra info
        $eventRepositoryBuilder = new \repositories\builders\EventRepositoryBuilder($this->app);
        $eventRepositoryBuilder->setCuratedList($curatedList, true);
        $events = $eventRepositoryBuilder->fetchAll();
        $this->assertEquals(1, count($events));
        $eventWithInfo = $events[0];
        $this->assertNull($eventWithInfo->getInCuratedListGroupId());
        $this->assertNull($eventWithInfo->getInCuratedListGroupSlug());
        $this->assertNull($eventWithInfo->getInCuratedListGroupTitle());
        $this->assertTrue($eventWithInfo->getIsEventInCuratedList());

        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setEventInformation($event);
        $curatedListsWithInfo = $curatedListRepoBuilder->fetchAll();
        $this->assertEquals(1, count($curatedListsWithInfo));
        $curatedListWithInfo = $curatedListsWithInfo[0];
        $this->assertEquals(true, $curatedListWithInfo->getIsEventInlist());


        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setContainsEvent($event);
        $curatedListsContainsEvent = $curatedListRepoBuilder->fetchAll();
        $this->assertEquals(1, count($curatedListsContainsEvent));


        // Remove event to list
        TimeSource::mock(2014, 5, 1, 9, 0, 0);
        $clRepo->removeEventFromCuratedList($event, $curatedList, $user);


        // Test After
        $eventRepositoryBuilder = new \repositories\builders\EventRepositoryBuilder($this->app);
        $eventRepositoryBuilder->setCuratedList($curatedList);
        $this->assertEquals(0, count($eventRepositoryBuilder->fetchAll()));

        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setEventInformation($event);
        $curatedListsWithInfo = $curatedListRepoBuilder->fetchAll();
        $this->assertEquals(1, count($curatedListsWithInfo));
        $curatedListWithInfo = $curatedListsWithInfo[0];
        $this->assertEquals(false, $curatedListWithInfo->getIsEventInlist());


        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setContainsEvent($event);
        $curatedListsContainsEvent = $curatedListRepoBuilder->fetchAll();
        $this->assertEquals(0, count($curatedListsContainsEvent));
    }
}
