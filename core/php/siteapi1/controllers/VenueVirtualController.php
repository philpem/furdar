<?php

namespace siteapi1\controllers;

use api1exportbuilders\EventListCSVBuilder;
use api1exportbuilders\ICalEventIdConfig;
use Silex\Application;
use site\forms\GroupNewForm;
use site\forms\GroupEditForm;
use site\forms\EventNewForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\VenueRepository;
use repositories\builders\GroupRepositoryBuilder;
use repositories\EventRepository;
use repositories\UserWatchesGroupRepository;
use repositories\builders\EventRepositoryBuilder;
use repositories\builders\HistoryRepositoryBuilder;
use api1exportbuilders\EventListICalBuilder;
use api1exportbuilders\EventListJSONBuilder;
use api1exportbuilders\EventListJSONPBuilder;
use api1exportbuilders\EventListATOMBeforeBuilder;
use api1exportbuilders\EventListATOMCreateBuilder;

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
    
    
    public function ical(Request $request, Application $app)
    {
        $ical = new EventListICalBuilder($app, $app['currentSite'], $app['currentTimeZone'], "Virtual Events", new ICalEventIdConfig($request->get('eventidconfig'), $request->server->all()));
        $ical->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $ical->build();
        return $ical->getResponse();
    }

    public function json(Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        $json = new EventListJSONBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $json->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $json->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $json->build();
        return $json->getResponse();
    }

    public function jsonp(Request $request, Application $app)
    {
        $ourRequest = new \Request($request);
        
        $jsonp = new EventListJSONPBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $jsonp->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $jsonp->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $jsonp->build();
        if (isset($_GET['callback'])) {
            $jsonp->setCallBackFunction($_GET['callback']);
        }
        return $jsonp->getResponse();
    }

    public function csv(Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        $csv = new EventListCSVBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $csv->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $csv->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $csv->build();
        return $csv->getResponse();
    }

    public function atomBefore(Request $request, Application $app)
    {
        $days = isset($_GET['days']) ? $_GET['days'] : null;
        $atom = new EventListATOMBeforeBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->setDaysBefore($days);
        $atom->setTitle('Virtual');
        $atom->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $atom->build();
        return $atom->getResponse();
    }
    

    public function atomCreate(Request $request, Application $app)
    {
        $atom = new EventListATOMCreateBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->setTitle('Virtual');
        $atom->getEventRepositoryBuilder()->setVenueVirtualOnly(true);
        $atom->build();
        return $atom->getResponse();
    }
}
