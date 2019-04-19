<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */



$app->match('/api1/curatedlist/{slug}/events.ical', 'org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::ical')
        ->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/api1/curatedlist/{slug}/events.json', 'org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::json')
        ->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/api1/curatedlist/{slug}/events.jsonp', 'org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::jsonp')
        ->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/api1/curatedlist/{slug}/events.csv', 'org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::csv')
        ->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/api1/curatedlist/{slug}/events.create.atom', 'org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::atomCreate')
        ->assert('slug', FRIENDLY_SLUG_REGEX);
$app->match('/api1/curatedlist/{slug}/events.before.atom', 'org\openacalendar\curatedlists\siteapi1\controllers\CuratedListController::atomBefore')
        ->assert('slug', FRIENDLY_SLUG_REGEX);
