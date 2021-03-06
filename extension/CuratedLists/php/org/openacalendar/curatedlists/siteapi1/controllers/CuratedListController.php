<?php

namespace org\openacalendar\curatedlists\siteapi1\controllers;

use api1exportbuilders\EventListCSVBuilder;
use api1exportbuilders\ICalEventIdConfig;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
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
class CuratedListController
{
    protected $parameters = array();
    
    protected function build($slug, Request $request, Application $app)
    {
        $this->parameters = array();

        if (strpos($slug, "-") > 0) {
            $slugBits = explode("-", $slug, 2);
            $slug = $slugBits[0];
        }

        $clr = new CuratedListRepository();
        $this->parameters['curatedlist'] = $clr->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['curatedlist']) {
            return false;
        }
        
        return true;
    }
    
    
    public function ical($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }
        
        $ical = new EventListICalBuilder($app, $app['currentSite'], $app['currentTimeZone'], $this->parameters['curatedlist']->getTitle(), new ICalEventIdConfig($request->get('eventidconfig'), $request->server->all()));
        $ical->getEventRepositoryBuilder()->setCuratedList($this->parameters['curatedlist']);
        $ical->build();
        return $ical->getResponse();
    }

    public function json($slug, Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }

        
        $json = new EventListJSONBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $json->getEventRepositoryBuilder()->setCuratedList($this->parameters['curatedlist']);
        $json->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $json->build();
        return $json->getResponse();
    }

    public function jsonp($slug, Request $request, Application $app)
    {
        $ourRequest = new \Request($request);

        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }

        
        $jsonp = new EventListJSONPBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $jsonp->getEventRepositoryBuilder()->setCuratedList($this->parameters['curatedlist']);
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
            $app->abort(404, "curatedlist does not exist.");
        }


        $csv = new EventListCSVBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $csv->getEventRepositoryBuilder()->setCuratedList($this->parameters['curatedlist']);
        $csv->setIncludeEventMedias($ourRequest->getGetOrPostBoolean("includeMedias", false));
        $csv->build();
        return $csv->getResponse();
    }

    public function atomBefore($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }

        $days = isset($_GET['days']) ? $_GET['days'] : null;
        $atom = new EventListATOMBeforeBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->setDaysBefore($days);
        $atom->setTitle($this->parameters['curatedlist']->getTitle());
        $atom->getEventRepositoryBuilder()->setCuratedList($this->parameters['curatedlist']);
        $atom->build();
        return $atom->getResponse();
    }
    

    public function atomCreate($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }

        
        $atom = new EventListATOMCreateBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->setTitle($this->parameters['curatedlist']->getTitle());
        $atom->getEventRepositoryBuilder()->setCuratedList($this->parameters['curatedlist']);
        $atom->build();
        return $atom->getResponse();
    }
}
