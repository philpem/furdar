<?php

use models\UserAccountModel;
use models\SiteModel;
use org\openacalendar\curatedlists\models\CuratedListModel;
use models\EventModel;
use models\GroupModel;
use repositories\SiteRepository;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use repositories\EventRepository;
use repositories\GroupRepository;
use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListPurgeTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userOther = new UserAccountModel();
        $userOther->setEmail("test2@jarofgreen.co.uk");
        $userOther->setUsername("test2");
        $userOther->setPassword("password");


        // We are deliberately using the UserAccountRepository from this extension so we have tests to cover instantiating and using this class
        // It extends the core one so has all methods.
        $userRepo = new \org\openacalendar\curatedlists\repositories\UserAccountRepository($this->app);
        $userRepo->create($user);
        $userRepo->create($userOther);
        
        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");
        
        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $user, array(), $this->getSiteQuotaUsedForTesting());

        $group = new GroupModel();
        $group->setTitle("test");
        $group->setDescription("test test");
        $group->setUrl("http://www.group.com");

        $groupRepo = new GroupRepository($this->app);
        $groupRepo->create($group, $site, $user);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0, 'Europe/London'));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0, 'Europe/London'));
        $event->setUrl("http://www.info.com");
        $event->setTicketUrl("http://www.tickets.com");

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);
        
        $curatedList = new CuratedListModel();
        $curatedList->setTitle("test");
        $curatedList->setDescription("test this!");
        
        $clRepo = new CuratedListRepository();
        $clRepo->create($curatedList, $site, $user);
        $clRepo->addEditorToCuratedList($userOther, $curatedList, $user);
        $clRepo->addEventtoCuratedList($event, $curatedList, $user);
        $clRepo->addGroupToCuratedList($group, $curatedList, $user);

        ## Test
        $this->assertNotNull($clRepo->loadBySlug($site, $curatedList->getSlug()));

        ## Purge!
        $clRepo->purge($curatedList);
                
        ## Test
        $this->assertNull($clRepo->loadBySlug($site, $curatedList->getSlug()));
    }
}
