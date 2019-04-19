<?php

namespace siteapi1\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\CountryModel;
use models\EventModel;
use repositories\CountryRepository;
use repositories\AreaRepository;
use repositories\builders\GroupRepositoryBuilder;
use repositories\EventRepository;
use repositories\LegacyRegionRepository;
use repositories\builders\EventRepositoryBuilder;
use repositories\builders\HistoryRepositoryBuilder;
use api1exportbuilders\EventListATOMBeforeBuilder;
use api1exportbuilders\EventListATOMCreateBuilder;
use api1exportbuilders\EventListJSONBuilder;
use api1exportbuilders\EventListJSONPBuilder;
use api1exportbuilders\EventListICalBuilder;

use repositories\builders\filterparams\EventFilterParams;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CountryLegacyRegionController
{
    protected $parameters = array();
    
    protected function build($slug, $regionslug, Request $request, Application $app)
    {
        $this->parameters = array();
        
        if ($slug == "1") {
            // OTC2 allowed ID's here to. There was only 1 country tho, and that was GB.
            $slug="GB";
        }
        
        $gr = new CountryRepository($app);
        $this->parameters['country'] = $gr->loadByTwoCharCode($slug);
        if (!$this->parameters['country']) {
            return false;
        }
        
        
        
        // TODO could check this country is or was valid for this site?
        
        $rr = new LegacyRegionRepository;
        $this->parameters['region'] = $rr->loadById($regionslug);
        if (!$this->parameters['region']) {
            return false;
        }
        
        $ar = new AreaRepository($app);
        $this->parameters['area'] = $ar->loadById($this->parameters['region']->getAreaId());
        
        
        return true;
    }
    
    
    
    
    public function ical($slug, $regionslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $regionslug, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.ical", 301);
    }

    
    public function json($slug, $regionslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $regionslug, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }


        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.json", 301);
    }
    
    
    public function jsonp($slug, $regionslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $regionslug, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        $callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.jsonp?callback=".urlencode($callback), 301);
    }
}
