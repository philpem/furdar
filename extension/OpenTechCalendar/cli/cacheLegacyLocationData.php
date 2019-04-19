<?php
define('APP_ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once APP_ROOT_DIR.'/vendor/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoload.php';

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


use repositories\builders\LegacyLocationRepositoryBuilder;
use repositories\builders\EventRepositoryBuilder;
use repositories\LegacyLocationRepository;
use repositories\AreaRepository;

$lr = new LegacyLocationRepository();
$ar = new AreaRepository($app);

$llb = new LegacyLocationRepositoryBuilder();
foreach ($llb->fetchAll() as $location) {
    $erb = new EventRepositoryBuilder($app);
    $erb->setArea($ar->loadById($location->getAreaId()));
    $erb->setIncludeDeleted(false);
    $erb->setAfterNow();
    $location->setCachedFutureEvents(count($erb->fetchAll()));
    $lr->editCache($location);
    print ".";
}

print "\n";
