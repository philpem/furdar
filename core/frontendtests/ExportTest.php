<?php

use Facebook\WebDriver\WebDriverBy;
use models\SiteModel;
use models\UserAccountModel;
use repositories\SiteRepository;
use repositories\UserAccountRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExportTest extends BaseFrontEndTest
{
    public function testExport1()
    {
        $this->addCountriesToTestDB();

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);

        $site = new SiteModel();
        $site->setTitle("Test1");
        $site->setSlug("test1");
        $site->setIsWebRobotsAllowed(false);

        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $user, array(), $this->getSiteQuotaUsedForTesting());


        $countryRepo = new \repositories\CountryRepository($this->app);
        $countryInSiteRepo = new \repositories\CountryInSiteRepository($this->app);
        $countryInSiteRepo->addCountryToSite($countryRepo->loadByTwoCharCode('GB'), $site, $user);

        $this->driver->get('http://openadevcalendar.co.uk:8082/');

        sleep($this->sleepOnActionWithNetwork);

        $exportLink = $this->driver->findElement(WebDriverBy::linkText('export'));
        $exportLink->click();

        sleep($this->sleepOnActionNoNetwork);

        $popup = $this->driver->findElement(WebDriverBy::id('ExportSharePopup'));

        $this->assertTrue($popup->isDisplayed());
    }
}
