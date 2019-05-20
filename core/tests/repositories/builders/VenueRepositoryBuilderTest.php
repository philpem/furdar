<?php

namespace tests\repositories\builders;

use models\UserAccountModel;
use models\SiteModel;
use models\VenueModel;
use repositories\builders\VenueRepositoryBuilder;
use repositories\CountryRepository;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\VenueRepository;
use TimeSource;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventRecurSetModelGetNewMontlyEventsTest extends \BaseAppWithDBTest
{
    public function testAddressCodeSearchRemoveSpaces()
    {
        $this->addCountriesToTestDB();

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

        $countryRepo = new CountryRepository($this->app);
        $gb = $countryRepo->loadByTwoCharCode('GB');

        $venue = new VenueModel();
        $venue->setTitle("test");
        $venue->setDescription("test test");
        $venue->setAddressCode("Eh1 2BR");
        $venue->setCountryId($gb->getId());

        $venueRepo = new VenueRepository($this->app);
        $venueRepo->create($venue, $site, $user);

        // Searching with remove spaces finds it!
        $vrb = new VenueRepositoryBuilder($this->app);
        $vrb->setFreeTextSearchAddressCode("EH12br", true);
        $venues = $vrb->fetchAll();

        $this->assertEquals(1, count($venues));

        // Searching with NO remove spaces is not found :-(
        $vrb = new VenueRepositoryBuilder($this->app);
        $vrb->setFreeTextSearchAddressCode("EH12br", false);
        $venues = $vrb->fetchAll();

        $this->assertEquals(0, count($venues));

        // And final check, searching for right spaces with No remove spaces will find it
        $vrb = new VenueRepositoryBuilder($this->app);
        $vrb->setFreeTextSearchAddressCode("EH1 2br", false);
        $venues = $vrb->fetchAll();

        $this->assertEquals(1, count($venues));
    }
}
