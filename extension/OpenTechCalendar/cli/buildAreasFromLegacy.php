<?php
define('APP_ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once APP_ROOT_DIR.'/vendor/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoloadCLI.php';
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


use repositories\AreaRepository;
use repositories\SiteRepository;
use repositories\CountryRepository;
use repositories\UserAccountRepository;
use repositories\builders\LegacyLocationRepositoryBuilder;
use repositories\builders\LegacyRegionRepositoryBuilder;
use models\AreaModel;

$areaRepository = new repositories\AreaRepository($app);

$userAccountRepo = new repositories\UserAccountRepository($app);
$user = $userAccountRepo->loadByEmail('james@jarofgreen.co.uk');

$countryRepository = new CountryRepository($app);
$country = $countryRepository->loadByTwoCharCode('GB');

$siteRepository = new SiteRepository($app);
$site = $siteRepository->loadById($CONFIG->singleSiteID);

$regionRepoBuilder = new repositories\builders\LegacyRegionRepositoryBuilder();

$statSetAreaOnRegion = $DB->prepare("UPDATE legacy_region_information SET area_id=:area_id WHERE id=:id");
$statSetAreaOnLocation = $DB->prepare("UPDATE legacy_location_information SET area_id=:area_id WHERE id=:id");

foreach ($regionRepoBuilder->fetchAll() as $region) {
    $regionArea = new AreaModel();
    $regionArea->setTitle($region->getTitle());
    
    $areaRepository->create($regionArea, null, $site, $country, $user);
    
    $statSetAreaOnRegion->execute(array('id'=>$region->getId(), 'area_id'=>$regionArea->getId()));
    print ".";
    $locationRepoBuilder = new LegacyLocationRepositoryBuilder();
    $locationRepoBuilder->setLegacyRegion($region);
    foreach ($locationRepoBuilder->fetchAll() as $location) {
        $area = new AreaModel();
        $area->setTitle($location->getTitle());

        $areaRepository->create($area, $regionArea, $site, $country, $user);

        $statSetAreaOnLocation->execute(array('id'=>$location->getId(), 'area_id'=>$area->getId()));
        print "_";
    }
}


print "\n";
