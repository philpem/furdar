<?php

use models\UserAccountModel;
use models\SiteModel;
use org\openacalendar\curatedlists\models\CuratedListModel;
use repositories\SiteRepository;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListCreateTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userStranger = new UserAccountModel();
        $userStranger->setEmail("test2@jarofgreen.co.uk");
        $userStranger->setUsername("test2");
        $userStranger->setPassword("password");

        // We are deliberately using the UserAccountRepository from this extension so we have tests to cover instantiating and using this class
        // It extends the core one so has all methods.
        $userRepo = new \org\openacalendar\curatedlists\repositories\UserAccountRepository($this->app);
        $userRepo->create($user);
        $userRepo->create($userStranger);
        
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
        
        // check loading works
        $this->checkCuratedListInTest1($clRepo->loadBySlug($site, 1));
        
        // check user perms work
        $curatedList = $clRepo->loadBySlug($site, 1);
        $this->assertFalse($curatedList->canUserEdit(null));
        $this->assertTrue($curatedList->canUserEdit($user));
        $this->assertFalse($curatedList->canUserEdit($userStranger));
        
        $clb = new CuratedListRepositoryBuilder($this->app);
        $clb->setUserCanEdit($user);
        $this->assertEquals(1, count($clb->fetchAll()));
        
        $clb = new CuratedListRepositoryBuilder($this->app);
        $clb->setUserCanEdit($userStranger);
        $this->assertEquals(0, count($clb->fetchAll()));
    }
    
    protected function checkCuratedListInTest1(CuratedListModel $curatedList)
    {
        $this->assertEquals("test this!", $curatedList->getDescription());
        $this->assertEquals("test", $curatedList->getTitle());
    }
}
