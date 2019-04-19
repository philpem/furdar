<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

// Old routes from OTC2
$app->match('/widgethelp/eventlist', "index\controllers\WidgetHelpController::listEvents");

$app->match('/events', "site\controllers\EventListController::index") ;
$app->match('/events/', "site\controllers\EventListController::index") ;

$app->match('/event/displayboard', "org\openacalendar\displayboard\site\controllers\DisplayBoardController::index");
$app->match('/event/displayboard/', "org\openacalendar\displayboard\site\controllers\DisplayBoardController::index");
$app->match('/event/displayboard/run', "org\openacalendar\displayboard\site\controllers\DisplayBoardController::run");
$app->match('/event/displayboard/run/', "org\openacalendar\displayboard\site\controllers\DisplayBoardController::run");


$app->match("/person/{username}/privateical/{accesskey}/", "index\controllers\PrivateUserController::icalAttendingWatching");
$app->match("/person/{username}/ical", "index\controllers\PublicUserController::ical");
$app->match("/person/{username}/ical/", "index\controllers\PublicUserController::ical");


// legacy urls
$app->match("/user/{username}", "index\controllers\PublicUserController::index");
$app->match("/user/{username}/", "index\controllers\PublicUserController::index");
$app->match("/user/{username}/private/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttendingWatching");
$app->match("/user/{username}/private/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttendingWatching");
$app->match("/user/{username}/privateA/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttending");
$app->match("/user/{username}/privateA/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttending");
$app->match("/user/{username}/privateAW/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttendingWatching");
$app->match("/user/{username}/privateAW/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttendingWatching");

// new url scheme; letters for what to include, narowwer scope letter first
$app->match("/person/{username}/privateA/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttending");
$app->match("/person/{username}/privateA/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttending");
$app->match("/person/{username}/privateAW/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttendingWatching");
$app->match("/person/{username}/privateAW/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttendingWatching");


$app->match('/event/ical/', "siteapi1\controllers\EventListController::ical");
$app->match('/Special:SpecialEventsExport/', "siteapi1\controllers\EventListController::ical");

$app->match('/group/{slug}/atom', "siteapi1\controllers\GroupController::atomCreate")
        ->assert('slug', '\d+');

$app->match('/location/virtual', "site\controllers\VenueVirtualController::show");
$app->match('/location/virtual/', "site\controllers\VenueVirtualController::show");
$app->match('/location/virtual/calendar', "site\controllers\VenueVirtualController::calendarNow") ;
$app->match('/location/virtual/calendar/', "site\controllers\VenueVirtualController::calendarNow") ;
$app->match('/location/virtual/calendar/{year}/{month}', "site\controllers\VenueVirtualController::calendar")
        ->assert('year', '\d+')
        ->assert('month', '\d+') ;
$app->match('/location/virtual/calendar/{year}/{month}/', "site\controllers\VenueVirtualController::calendar")
        ->assert('year', '\d+')
        ->assert('month', '\d+') ;
$app->match('/location/virtual/history', "site\controllers\VenueVirtualController::history");

$app->match('/location/virtual/ical', "siteapi1\controllers\VenueVirtualController::ical") ;
$app->match('/location/virtual/json', "siteapi1\controllers\VenueVirtualController::json") ;
$app->match('/location/virtual/jsonp', "siteapi1\controllers\VenueVirtualController::jsonp") ;
$app->match('/location/virtual/atom', "siteapi1\controllers\VenueVirtualController::atomCreate") ;


$app->match('/list', "org\openacalendar\curatedlists\site\controllers\CuratedListListController::index");
$app->match('/list/', "org\openacalendar\curatedlists\site\controllers\CuratedListListController::index");

$app->match('/list/{slug}', "org\openacalendar\curatedlists\site\controllers\CuratedListController::show")
        ->assert('slug', '\d+');
$app->match('/list/{slug}/', "org\openacalendar\curatedlists\site\controllers\CuratedListController::show")
        ->assert('slug', '\d+');
$app->match('/list/{slug}/curators', "org\openacalendar\curatedlists\site\controllers\CuratedListController::curators")
        ->assert('slug', '\d+')
        ->before($canChangeSite);
$app->match('/list/{slug}/calendar', "org\openacalendar\curatedlists\site\controllers\CuratedListController::calendarNow") ;
$app->match('/list/{slug}/calendar/', "org\openacalendar\curatedlists\site\controllers\CuratedListController::calendarNow") ;
$app->match('/list/{slug}/calendar/{year}/{month}', "org\openacalendar\curatedlists\site\controllers\CuratedListController::calendar")
        ->assert('slug', '\d+')
        ->assert('year', '\d+')
        ->assert('month', '\d+') ;
$app->match('/list/{slug}/calendar/{year}/{month}/', "org\openacalendar\curatedlists\site\controllers\CuratedListController::calendar")
        ->assert('slug', '\d+')
        ->assert('year', '\d+')
        ->assert('month', '\d+') ;

$app->match('/list/{slug}/ical', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::ical")
        ->assert('slug', '\d+');
$app->match('/list/{slug}/json', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::json")
        ->assert('slug', '\d+');
$app->match('/list/{slug}/jsonp', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::jsonp")
        ->assert('slug', '\d+');
$app->match('/list/{slug}/atom', "org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::atomCreate")
        ->assert('slug', '\d+');

$app->match('/events/ical', "siteapi1\controllers\EventListController::ical");
$app->match('/events/ical/', "siteapi1\controllers\EventListController::ical");


// Legacy data

$app->match('/country/{slug}/region/{regionslug}', "site\controllers\CountryLegacyRegionController::show")
        ->assert('regionslug', '\d+') ;
$app->match('/country/{slug}/region/{regionslug}/', "site\controllers\CountryLegacyRegionController::show")
        ->assert('regionslug', '\d+') ;
$app->match('/country/{slug}/region/{regionslug}/ical', "siteapi1\controllers\CountryLegacyRegionController::ical")
        ->assert('regionslug', '\d+') ;
$app->match('/country/{slug}/region/{regionslug}/json', "siteapi1\controllers\CountryLegacyRegionController::json")
        ->assert('regionslug', '\d+') ;
$app->match('/country/{slug}/region/{regionslug}/jsonp', "siteapi1\controllers\CountryLegacyRegionController::jsonp")
        ->assert('regionslug', '\d+');




$app->match('/location', "site\controllers\IndexController::index");
$app->match('/location/', "site\controllers\IndexController::index");

$app->match('/location/{id}', "site\controllers\LegacyLocationController::show")
        ->assert('id', '\d+');
$app->match('/location/{id}/', "site\controllers\LegacyLocationController::show")
        ->assert('id', '\d+');
$app->match('/location/{id}/calendar', "site\controllers\LegacyLocationController::calendarNow")
        ->assert('id', '\d+');
$app->match('/location/{id}/calendar/{year}/{month}', "site\controllers\LegacyLocationController::calendar")
        ->assert('id', '\d+');
$app->match('/location/{id}/calendar/{year}/{month}/', "site\controllers\LegacyLocationController::calendar")
        ->assert('id', '\d+'); #otc2 legacy
$app->match('/location/{id}/allEvents', "site\controllers\LegacyLocationController::allEvents")
        ->assert('id', '\d+');
$app->match('/location/{id}/groups', "site\controllers\LegacyLocationController::groups")
        ->assert('id', '\d+');
$app->match('/location/{id}/ical', "siteapi1\controllers\LegacyLocationController::ical")
        ->assert('id', '\d+');
$app->match('/location/{id}/atom', "siteapi1\controllers\LegacyLocationController::atomCreate")
        ->assert('id', '\d+');
$app->match('/location/{id}/atomCreate', "siteapi1\controllers\LegacyLocationController::atomCreate")
        ->assert('id', '\d+');
$app->match('/location/{id}/atomBefore', "siteapi1\controllers\LegacyLocationController::atomBefore")
        ->assert('id', '\d+');
$app->match('/location/{id}/json', "siteapi1\controllers\LegacyLocationController::json")
        ->assert('id', '\d+');
$app->match('/location/{id}/jsonp', "siteapi1\controllers\LegacyLocationController::jsonp")
        ->assert('id', '\d+');


// Game!

$app->match('/game/', "site\controllers\Game1Controller::index");
$app->match('/game/data.json', "site\controllers\Game1Controller::dataJson");

// Other special pages for us

$app->match('/usereditable/', "site\controllers\OpenTechCalendarController::userEditable");

$app->match('/area/{slug}/otcfrontpage.json', "site\controllers\OpenTechCalendarController::frontPageForArea");
$app->match('/country/{code}/otcfrontpage.json', "site\controllers\OpenTechCalendarController::frontPageForCountry");
