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
class VenueController
{
    protected $parameters = array();
    
    protected function build($slug, Request $request, Application $app)
    {
        $this->parameters = array();

        if (strpos($slug, "-") > 0) {
            $slugBits = explode("-", $slug, 2);
            $slug = $slugBits[0];
        }

        $vr = new VenueRepository($app);
        $this->parameters['venue'] = $vr->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['venue']) {
            return false;
        }
        
        return true;
    }
    
    
    public function ical($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }
        
        $ical = new EventListICalBuilder($app, $app['currentSite'], $app['currentTimeZone'], $this->parameters['venue']->getTitle(), new ICalEventIdConfig($request->get('eventidconfig'), $request->server->all()));
        $ical->getEventRepositoryBuilder()->setVenue($this->parameters['venue']);
        $ical->build();
        return $ical->getResponse();
    }

    public function json($slug, Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }

        
        $json = new EventListJSONBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $json->getEventRepositoryBuilder()->setVenue($this->parameters['venue']);
        $json->addOtherDataVenue($this->parameters['venue']);
        $json->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $json->build();
        return $json->getResponse();
    }

    public function jsonp($slug, Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }

        
        $jsonp = new EventListJSONPBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $jsonp->getEventRepositoryBuilder()->setVenue($this->parameters['venue']);
        $jsonp->addOtherDataVenue($this->parameters['venue']);
        $jsonp->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $jsonp->build();
        if (isset($_GET['callback'])) {
            $jsonp->setCallBackFunction($_GET['callback']);
        }
        return $jsonp->getResponse();
    }

    public function csv($slug, Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }


        $csv = new EventListCSVBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $csv->getEventRepositoryBuilder()->setVenue($this->parameters['venue']);
        $csv->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $csv->build();
        return $csv->getResponse();
    }

    public function atomBefore($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }

        $days = isset($_GET['days']) ? $_GET['days'] : null;
        $atom = new EventListATOMBeforeBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->setDaysBefore($days);
        $atom->setTitle($this->parameters['venue']->getTitle());
        $atom->getEventRepositoryBuilder()->setVenue($this->parameters['venue']);
        $atom->build();
        return $atom->getResponse();
    }
    

    public function atomCreate($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }

        
        $atom = new EventListATOMCreateBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->setTitle($this->parameters['venue']->getTitle());
        $atom->getEventRepositoryBuilder()->setVenue($this->parameters['venue']);
        $atom->build();
        return $atom->getResponse();
    }
}
