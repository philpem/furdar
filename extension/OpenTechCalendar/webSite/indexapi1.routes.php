<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
############################ OUR OWN ONES THAT SHOULD GO TO OPEN SOURCE SOME DAY

$app->match('/api1/venue/{slug}/extrainfo.json', "siteapi1\controllers\VenueExtraController::json")
    ->assert('slug', '\d+');


$app->match('/api1/area/{slug}/groups.json', "siteapi1\controllers\AreaExtraController::groupsJson")
    ->assert('slug', '\d+') ;

$app->match('/api1/group/{groupSlug}/area/{areaSlug}/eventsForSchedule.json', "siteapi1\controllers\GroupAreaExtraController::eventsForScheduleJson")
    ->assert('slug', '\d+') ;

############################ OLD LEGACY

$app->match('/api1/event/ical', "siteapi1\controllers\EventListController::ical");
$app->match('/api1/event/json', "siteapi1\controllers\EventListController::json");
$app->match('/api1/event/jsonp', "siteapi1\controllers\EventListController::jsonp");
$app->match('/api1/event/atomCreate', "siteapi1\controllers\EventListController::atomCreate");
$app->match('/api1/event/atomBefore', "siteapi1\controllers\EventListController::atomBefore");

$app->match('/api1/event/{slug}/ical', "siteapi1\controllers\EventController::ical")
        ->assert('slug', '\d+');
$app->match('/api1/event/{slug}/json', "siteapi1\controllers\EventController::json")
        ->assert('slug', '\d+');
$app->match('/api1/event/{slug}/jsonp', "siteapi1\controllers\EventController::jsonp")
        ->assert('slug', '\d+');

$app->match('/api1/group/json', "siteapi1\controllers\GroupListController::json");

$app->match('/api1/group/{slug}/ical', "siteapi1\controllers\GroupController::ical")
        ->assert('slug', '\d+');
$app->match('/api1/group/{slug}/json', "siteapi1\controllers\GroupController::json")
        ->assert('slug', '\d+');
$app->match('/api1/group/{slug}/jsonp', "siteapi1\controllers\GroupController::jsonp")
        ->assert('slug', '\d+');
$app->match('/api1/group/{slug}/atomCreate', "siteapi1\controllers\GroupController::atomCreate")
        ->assert('slug', '\d+');
$app->match('/api1/group/{slug}/atomBefore', "siteapi1\controllers\GroupController::atomBefore")
        ->assert('slug', '\d+');

$app->match('/api1/venue/virtual/ical', "siteapi1\controllers\VenueVirtualController::ical") ;
$app->match('/api1/venue/virtual/json', "siteapi1\controllers\VenueVirtualController::json") ;
$app->match('/api1/venue/virtual/jsonp', "siteapi1\controllers\VenueVirtualController::jsonp") ;
$app->match('/api1/venue/virtual/atomCreate', "siteapi1\controllers\VenueVirtualController::atomCreate") ;
$app->match('/api1/venue/virtual/atomBefore', "siteapi1\controllers\VenueVirtualController::atomBefore") ;

$app->match('/api1/curatedlist/{slug}/ical', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::ical")
        ->assert('slug', '\d+');
$app->match('/api1/curatedlist/{slug}/json', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::json")
        ->assert('slug', '\d+');
$app->match('/api1/curatedlist/{slug}/jsonp', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::jsonp")
        ->assert('slug', '\d+');
$app->match('/api1/curatedlist/{slug}/atomCreate', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::atomCreate")
        ->assert('slug', '\d+');
$app->match('/api1/curatedlist/{slug}/atomBefore', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::atomBefore")
        ->assert('slug', '\d+');


$app->match('/api1/venue/{slug}/ical', "siteapi1\controllers\VenueController::ical")
        ->assert('slug', '\d+');
$app->match('/api1/venue/{slug}/json', "siteapi1\controllers\VenueController::json")
        ->assert('slug', '\d+');
$app->match('/api1/venue/{slug}/jsonp', "siteapi1\controllers\VenueController::jsonp")
        ->assert('slug', '\d+');
$app->match('/api1/venue/{slug}/atomCreate', "siteapi1\controllers\VenueController::atomCreate")
        ->assert('slug', '\d+');
$app->match('/api1/venue/{slug}/atomBefore', "siteapi1\controllers\VenueController::atomBefore")
        ->assert('slug', '\d+');

$app->match('/api1/country/{slug}/ical', "siteapi1\controllers\CountryController::eventsIcal");
$app->match('/api1/country/{slug}/json', "siteapi1\controllers\CountryController::eventsJson");
$app->match('/api1/country/{slug}/jsonp', "siteapi1\controllers\CountryController::eventsJsonp");
$app->match('/api1/country/{slug}/atomCreate', "siteapi1\controllers\CountryController::eventsAtomCreate");
$app->match('/api1/country/{slug}/atomBefore', "siteapi1\controllers\CountryController::eventsAtomBefore");

$app->match('/api1/history/atom', "siteapi1\controllers\HistoryListController::atom");
