<?php

namespace site\controllers;

use Silex\Application;
use site\forms\VenueEditForm;
use Symfony\Component\HttpFoundation\Request;
use models\VenueModel;
use repositories\VenueRepository;
use repositories\CountryRepository;
use repositories\builders\EventRepositoryBuilder;
use repositories\builders\HistoryRepositoryBuilder;

use repositories\builders\filterparams\EventFilterParams;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueVirtualController
{
    protected $parameters = array();
    
    public function show(Request $request, Application $app)
    {
        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setIncludeMediasSlugs(true);
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        if ($app['currentUser']) {
            $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $this->parameters['eventListFilterParams']->set($_GET);
        
        $this->parameters['events'] = $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->fetchAll();
        
        return $app['twig']->render('site/venuevirtual/show.html.twig', $this->parameters);
    }
    
    
    public function calendarNow(Request $request, Application $app)
    {
        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setFallBackFrom(true);
        $this->parameters['eventListFilterParams']->set($_GET);

        $this->parameters['calendar'] = new \RenderCalendar($app, $this->parameters['eventListFilterParams']);

        if ($app['currentUser']) {
            $this->parameters['calendar']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $this->parameters['calendar']->byDate(\TimeSource::getDateTime(), 31, true);
        
        list($this->parameters['prevYear'], $this->parameters['prevMonth'], $this->parameters['nextYear'], $this->parameters['nextMonth']) = $this->parameters['calendar']->getPrevNextLinksByMonth();
        
        $this->parameters['pageTitle'] = "Virtual";
        $this->parameters['venueVirtual'] = true;
        return $app['twig']->render('/site/venuevirtual/calendar.monthly.html.twig', $this->parameters);
    }
    
    public function calendar($year, $month, Request $request, Application $app)
    {
        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setFallBackFrom(true);
        $this->parameters['eventListFilterParams']->set($_GET);

        $this->parameters['calendar'] = new \RenderCalendar($app, $this->parameters['eventListFilterParams']);

        if ($app['currentUser']) {
            $this->parameters['calendar']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
            $this->parameters['showCurrentUserOptions'] = true;
        }
        $this->parameters['calendar']->byMonth($year, $month, true);
        
        list($this->parameters['prevYear'], $this->parameters['prevMonth'], $this->parameters['nextYear'], $this->parameters['nextMonth']) = $this->parameters['calendar']->getPrevNextLinksByMonth();
        
        $this->parameters['pageTitle'] = "Virtual";
        $this->parameters['venueVirtual'] = true;
        return $app['twig']->render('/site/venuevirtual/calendar.monthly.html.twig', $this->parameters);
    }
    
    public function history(Request $request, Application $app)
    {
        $historyRepositoryBuilder = new HistoryRepositoryBuilder($app);
        $historyRepositoryBuilder->setVenueVirtualOnly(true);
        $this->parameters['historyItems'] = $historyRepositoryBuilder->fetchAll();
        
        return $app['twig']->render('site/venuevirtual/history.html.twig', $this->parameters);
    }
}
