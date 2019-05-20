<?php

use models\UserAccountModel;
use models\SiteModel;
use models\VenueModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\VenueRepository;
use repositories\CountryRepository;
use repositories\builders\VenueRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueCreateTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $this->addCountriesToTestDB();

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
        
        $countryRepo = new CountryRepository($this->app);
        $gb = $countryRepo->loadByTwoCharCode('GB');

        $venue = new VenueModel();
        $venue->setTitle("test");
        $venue->setDescription("test test");
        $venue->setCountryId($gb->getId());

        $venueRepo = new VenueRepository($this->app);
        $venueRepo->create($venue, $site, $user);
        
        $this->checkVenueInTest1($venueRepo->loadById($venue->getId()));
        $this->checkVenueInTest1($venueRepo->loadBySlug($site, $venue->getSlug()));
        
        $grb = new VenueRepositoryBuilder($this->app);
        $grb->setFreeTextsearch('test');
        $this->assertEquals(1, count($grb->fetchAll()));
        
        $grb = new VenueRepositoryBuilder($this->app);
        $grb->setFreeTextsearch('cats');
        $this->assertEquals(0, count($grb->fetchAll()));
    }
    
    protected function checkVenueInTest1(VenueModel $venue)
    {
        $this->assertEquals("test test", $venue->getDescription());
        $this->assertEquals("test", $venue->getTitle());
        $this->assertNotNull($venue->getCountryId());
    }
}
