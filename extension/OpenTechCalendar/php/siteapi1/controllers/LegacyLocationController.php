<?php

namespace siteapi1\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\LegacyLocationRepository;
use repositories\AreaRepository;
use api1exportbuilders\EventListATOMBeforeBuilder;
use api1exportbuilders\EventListATOMCreateBuilder;
use api1exportbuilders\EventListJSONBuilder;
use api1exportbuilders\EventListJSONPBuilder;
use api1exportbuilders\EventListICalBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class LegacyLocationController
{
    protected $parameters = array();
    
    protected function build($id, Request $request, Application $app)
    {
        $this->parameters = array();
        
    
        $lr = new LegacyLocationRepository();
        $this->parameters['location'] = $lr->loadById($id);
        if (!$this->parameters['location']) {
            return false;
        }

        $ar = new AreaRepository($app);
        $this->parameters['area'] = $ar->loadById($this->parameters['location']->getAreaId());
        
        
        return true;
    }

    public function ical($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.ical", 301);
    }
    
    
    public function atomCreate($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.create.atom", 301);
    }
    
    
    public function atomBefore($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        $days = isset($_GET['days']) ? intval($_GET['days']) : 3;

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.before.atom?days=".$days, 301);
    }
    
    public function json($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.json", 301);
    }
    
    
    public function jsonp($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }

        $callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';

        return $app->redirect("/api1/area/".$this->parameters['area']->getSlug()."/events.jsonp?callback=".urlencode($callback), 301);
    }
}
