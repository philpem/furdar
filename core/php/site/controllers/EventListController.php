<?php

namespace site\controllers;

use Silex\Application;
use repositories\builders\filterparams\EventFilterParams;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventListController
{
    public function index(Application $app)
    {
        $params = new EventFilterParams($app, null, $app['currentSite']);
        $params->getEventRepositoryBuilder()->setIncludeAreaInformation(true);
        $params->getEventRepositoryBuilder()->setIncludeVenueInformation(true);
        $params->getEventRepositoryBuilder()->setIncludeMediasSlugs(true);
        $params->setHasCountryControl($app['currentSite']->getCachedIsMultipleCountries());
        $params->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $app['currentSiteHasOneCountry']);
        $params->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $params->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        if ($app['currentUser']) {
            $params->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $params->set($_GET);
        
        $events = $params->getEventRepositoryBuilder()->fetchAll();
        
        return $app['twig']->render('site/eventlist/index.html.twig', array(
                'eventListFilterParams'=>$params,
                'events'=>$events,
            ));
    }
    
    
    
    public function calendarNow(Application $app)
    {
        $params = new EventFilterParams($app, null, $app['currentSite']);
        $params->setHasDateControls(false);
        $params->setHasCountryControl($app['currentSite']->getCachedIsMultipleCountries());
        $params->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $app['currentSiteHasOneCountry']);
        $params->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $params->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $params->setFallBackFrom(true);
        $params->set($_GET);

        $cal = new \RenderCalendar($app, $params);
        if ($app['currentUser']) {
            $cal->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $cal->byDate(\TimeSource::getDateTime(), 31, true);
        
        list($prevYear, $prevMonth, $nextYear, $nextMonth) = $cal->getPrevNextLinksByMonth();

        return $app['twig']->render('/site/eventlist/calendar.monthly.html.twig', array(
                'calendar'=>$cal,
                'prevYear' => $prevYear,
                'prevMonth' => $prevMonth,
                'nextYear' => $nextYear,
                'nextMonth' => $nextMonth,
                'pageTitle' => 'Calendar',
                'eventListFilterParams' => $params,
            ));
    }
    
    public function calendar($year, $month, Application $app)
    {
        $params = new EventFilterParams($app, null, $app['currentSite']);
        $params->setHasDateControls(false);
        $params->setHasCountryControl($app['currentSite']->getCachedIsMultipleCountries());
        $params->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $app['currentSiteHasOneCountry']);
        $params->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $params->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $params->setFallBackFrom(true);
        $params->set($_GET);

        $cal = new \RenderCalendar($app, $params);
        $cal->getEventRepositoryBuilder()->setSite($app['currentSite']);
        $cal->getEventRepositoryBuilder()->setIncludeDeleted(false);
        if ($app['currentUser']) {
            $cal->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $cal->byMonth($year, $month, true);

        list($prevYear, $prevMonth, $nextYear, $nextMonth) = $cal->getPrevNextLinksByMonth();

        return $app['twig']->render('/site/eventlist/calendar.monthly.html.twig', array(
                'calendar'=>$cal,
                'prevYear' => $prevYear,
                'prevMonth' => $prevMonth,
                'nextYear' => $nextYear,
                'nextMonth' => $nextMonth,
                'pageTitle' => 'Calendar',
                'eventListFilterParams' => $params,
            ));
    }
}
