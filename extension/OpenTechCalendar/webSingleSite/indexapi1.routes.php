<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


$app->match('/api1/legacylocation/{id}/ical', "siteapi1\controllers\LegacyLocationController::ical")
        ->assert('id', '\d+');
$app->match('/api1/legacylocation/{id}/atomCreate', "siteapi1\controllers\LegacyLocationController::atomCreate")
        ->assert('id', '\d+');
$app->match('/api1/legacylocation/{id}/atomBefore', "siteapi1\controllers\LegacyLocationController::atomBefore")
        ->assert('id', '\d+');
$app->match('/api1/legacylocation/{id}/json', "siteapi1\controllers\LegacyLocationController::json")
        ->assert('id', '\d+');
$app->match('/api1/legacylocation/{id}/jsonp', "siteapi1\controllers\LegacyLocationController::jsonp")
        ->assert('id', '\d+');


$app->match('/api1/country/{slug}/legacyregion/{regionslug}/ical', "siteapi1\controllers\CountryLegacyRegionController::ical")
        ->assert('regionslug', '\d+') ;
$app->match('/api1/country/{slug}/legacyregion/{regionslug}/atomCreate', "siteapi1\controllers\CountryLegacyRegionController::atomCreate")
        ->assert('regionslug', '\d+') ;
$app->match('/api1/country/{slug}/legacyregion/{regionslug}/atomBefore', "siteapi1\controllers\CountryLegacyRegionController::atomBefore")
        ->assert('regionslug', '\d+') ;
$app->match('/api1/country/{slug}/legacyregion/{regionslug}/json', "siteapi1\controllers\CountryLegacyRegionController::json")
        ->assert('regionslug', '\d+') ;
$app->match('/api1/country/{slug}/legacyregion/{regionslug}/jsonp', "siteapi1\controllers\CountryLegacyRegionController::jsonp")
        ->assert('regionslug', '\d+') ;
