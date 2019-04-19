<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
$app->match('/event/ical', "siteapi1\controllers\EventListController::ical"); // deprecated; left for old users
$app->match('/event/json', "siteapi1\controllers\EventListController::json"); // deprecated; left for old users
$app->match('/event/jsonp', "siteapi1\controllers\EventListController::jsonp"); // deprecated; left for old users
$app->match('/event/atom', "siteapi1\controllers\EventListController::atomCreate"); // deprecated; left for old users
$app->match('/event/atom/', "siteapi1\controllers\EventListController::atomCreate"); // deprecated; left for old users
$app->match('/event/atomCreate', "siteapi1\controllers\EventListController::atomCreate"); // deprecated; left for old users
$app->match('/event/atomBefore', "siteapi1\controllers\EventListController::atomBefore"); // deprecated; left for old users

$app->match('/event/{slug}/ical', "siteapi1\controllers\EventController::ical")
        ->assert('slug', '\d+'); // deprecated; left for old users
$app->match('/event/{slug}/json', "siteapi1\controllers\EventController::json")
        ->assert('slug', '\d+'); // deprecated; left for old users
$app->match('/event/{slug}/jsonp', "siteapi1\controllers\EventController::jsonp")
        ->assert('slug', '\d+'); // deprecated; left for old users

$app->match('/group/{slug}/ical', "siteapi1\controllers\GroupController::ical")
        ->assert('slug', '\d+'); // deprecated; left for old users
$app->match('/group/{slug}/json', "siteapi1\controllers\GroupController::json")
        ->assert('slug', '\d+'); // deprecated; left for old users
$app->match('/group/{slug}/jsonp', "siteapi1\controllers\GroupController::jsonp")
        ->assert('slug', '\d+'); // deprecated; left for old users
$app->match('/group/{slug}/atomCreate', "siteapi1\controllers\GroupController::atomCreate")
        ->assert('slug', '\d+'); // deprecated; left for old users
$app->match('/group/{slug}/atomBefore', "siteapi1\controllers\GroupController::atomBefore")
        ->assert('slug', '\d+'); // deprecated; left for old users



$app->match('/country/{slug}/ical', "siteapi1\controllers\CountryController::eventsIcal"); // deprecated; left for old users
$app->match('/country/{slug}/json', "siteapi1\controllers\CountryController::eventsJson"); // deprecated; left for old users
$app->match('/country/{slug}/jsonp', "siteapi1\controllers\CountryController::eventsJsonp"); // deprecated; left for old users
$app->match('/country/{slug}/atomCreate', "siteapi1\controllers\CountryController::eventsAtomCreate"); // deprecated; left for old users
$app->match('/country/{slug}/atomBefore', "siteapi1\controllers\CountryController::eventsAtomBefore"); // deprecated; left for old users
