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

$countries = ['GB', 'IE', 'IM', 'BE', 'CA', 'DK', 'FR', 'DE', 'NL', 'NO', 'PL', 'SE', 'CH', 'US'];

#$parentAreasAllowedUK = array('England','Scotland','Wales','Northern Ireland');
#$parentAreasAllowedUKOther = array('Isle of Man');

foreach ($countries as $c) {

	$country = $countryRepository->loadByTwoCharCode($c);
	print "$c (id: " . $country->getId() . "): \n";

	################################################################################ UK

	$arb = new \repositories\builders\AreaRepositoryBuilder($app);
	$arb->setCountry($country);
	$arb->setNoParentArea(true);
	$arb->setIncludeDeleted(false);
	foreach ($arb->fetchAll() as $parentUKArea) {
		echo "  " . $parentUKArea->getTitle();
		if ($parentUKArea->getCachedFutureEvents() > 0 /*&& in_array($parentUKArea->getTitle(), $parentAreasAllowedUK)*/) {
			echo " -> " . $parentUKArea->getCachedFutureEvents() . " future events\n";
			$parentData = array(
				'children'=>array(),
				'type'=>'area',
				'title'=> $country->getTitle() . ": " . $parentUKArea->getTitle(),
				'slug'=>$parentUKArea->getSlugForUrl(),
				'count'=>$parentUKArea->getCachedFutureEvents(),
			);

			$carb = new \repositories\builders\AreaRepositoryBuilder($app);
			$carb->setCountry($country);
			$carb->setParentArea($parentUKArea);
			$carb->setIncludeDeleted(false);
			$n = $parentUKArea->getCachedFutureEvents();
			foreach ($carb->fetchAll() as $childUKArea) {
				if ($childUKArea->getCachedFutureEvents() > 0) {
					echo "    --> child area with events '" . $childUKArea->getTitle() . "' - " . $childUKArea->getCachedFutureEvents() . " events\n";
					$parentData['children'][] = array(
						'type'=>'area',
						'title'=> $childUKArea->getTitle(),
						'slug'=>$childUKArea->getSlugForUrl(),
						'count'=>$childUKArea->getCachedFutureEvents(),
					);

					// keep track of how many we've processed
					$n -= $childUKArea->getCachedFutureEvents();
				}
			}
			if ($n > 0) {
				echo "    NOTE: $n unprocessed events... adding an 'other' box\n";
				$parentData['children'][] = array(
					'type' => 'area',
					'title' => '(Other)',
					'slug' => $parentUKArea->getSlugForUrl(),
					'count' => $n,
				);
			}
			$data[] = $parentData;
/*
		} elseif ($parentUKArea->getCachedFutureEvents() > 0 && in_array($parentUKArea->getTitle(), $parentAreasAllowedUKOther)) {
			echo "--> child area has events '" . $childUKArea->getTitle() . "'\n";
			$parentData = array(
				'type'=>'area',
				'title'=> $parentUKArea->getTitle(),
				'slug'=>$parentUKArea->getSlugForUrl(),
				'count'=>$parentUKArea->getCachedFutureEvents(),
			);
			$dataOther[] = $parentData;
		}
*/
		} else {
			echo	"\n";
		}
	}
}


$out = "<" ."?php\n\n\$FRONTPAGEAREAS = ". var_export($data, true).";\n\n\$FRONTPAGEAREASOTHER = ". var_export($dataOther, true).";\n\n";
file_put_contents(APP_ROOT_DIR.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'frontPageAreas.php', $out);
