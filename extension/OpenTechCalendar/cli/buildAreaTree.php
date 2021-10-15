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

$areaRepository = new AreaRepository($app);
$countryRepository = new CountryRepository($app);


################################################################################ Vars
$data = array();
$dataOther = array();

$uk = $countryRepository->loadByTwoCharCode('GB');

################################################################################ UK

$parentAreasAllowedUK = array('England','Scotland','Wales','Northern Ireland');

$parentAreasAllowedUKOther = array('Isle of Man');

$arb = new \repositories\builders\AreaRepositoryBuilder($app);
$arb->setCountry($uk);
$arb->setNoParentArea(true);
$arb->setIncludeDeleted(false);
foreach ($arb->fetchAll() as $parentUKArea) {
	if ($parentUKArea->getCachedFutureEvents() > 0 /*&& in_array($parentUKArea->getTitle(), $parentAreasAllowedUK)*/) {
	echo $parentUKArea->getTitle();
	echo $parentUKArea->getCachedFutureEvents();
       echo	"\n";
        $parentData = array(
            'children'=>array(),
            'type'=>'area',
            'title'=> $parentUKArea->getTitle(),
            'slug'=>$parentUKArea->getSlugForUrl(),
            'count'=>$parentUKArea->getCachedFutureEvents(),
            );
        $carb = new \repositories\builders\AreaRepositoryBuilder($app);
 #       $carb->setCountry($uk);
        $carb->setParentArea($parentUKArea);
        $carb->setIncludeDeleted(false);
        foreach ($carb->fetchAll() as $childUKArea) {
            if ($childUKArea->getCachedFutureEvents() > 0) {
                $parentData['children'][] = array(
                    'type'=>'area',
                    'title'=> $childUKArea->getTitle(),
                    'slug'=>$childUKArea->getSlugForUrl(),
                    'count'=>$childUKArea->getCachedFutureEvents(),
                );
            }
        }
        $data[] = $parentData;
	} elseif ($parentUKArea->getCachedFutureEvents() > 0 /*&& in_array($parentUKArea->getTitle(), $parentAreasAllowedUKOther)*/) {
        $parentData = array(
            'type'=>'area',
            'title'=> $parentUKArea->getTitle(),
            'slug'=>$parentUKArea->getSlugForUrl(),
            'count'=>$parentUKArea->getCachedFutureEvents(),
            );
        $dataOther[] = $parentData;
    }
}



$out = "<" ."?php\n\n\$FRONTPAGEAREAS = ". var_export($data, true).";\n\n\$FRONTPAGEAREASOTHER = ". var_export($dataOther, true).";\n\n";
file_put_contents(APP_ROOT_DIR.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'frontPageAreas.php', $out);
