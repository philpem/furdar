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
class VenueDeleteTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $this->app['timesource']->mock(2014, 1, 1, 0, 0, 0);

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

        $this->app['timesource']->mock(2014, 1, 1, 1, 0, 0);
        $venueRepo = new VenueRepository($this->app);
        $venueRepo->create($venue, $site, $user);

        $this->app['timesource']->mock(2014, 1, 1, 2, 0, 0);
        $venueRepo->delete($venue, $user);


        $this->checkVenueInTest1($venueRepo->loadById($venue->getId()));
        $this->checkVenueInTest1($venueRepo->loadBySlug($site, $venue->getSlug()));
        
        $vrb = new VenueRepositoryBuilder($this->app);
        $vrb->setIncludeDeleted(true);
        $this->assertEquals(1, count($vrb->fetchAll()));

        $vrb = new VenueRepositoryBuilder($this->app);
        $vrb->setIncludeDeleted(false);
        $this->assertEquals(0, count($vrb->fetchAll()));
    }
    
    protected function checkVenueInTest1(VenueModel $venue)
    {
        $this->assertEquals("test test", $venue->getDescription());
        $this->assertEquals("test", $venue->getTitle());
        $this->assertNotNull($venue->getCountryId());
    }
}
