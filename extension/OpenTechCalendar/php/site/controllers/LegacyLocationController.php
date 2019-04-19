<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\LegacyLocationRepository;


use repositories\builders\filterparams\EventFilterParams;

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
        
        return true;
    }
    
    public function show($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }
        
        return $app->redirect("/area/".$this->parameters['location']->getAreaId(), 301);
    }
    
    
    public function calendarNow($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Group does not exist.");
        }

        
        $now = \TimeSource::getDateTime();
        return $app->redirect("/area/".$this->parameters['location']->getAreaId()."/calendar", 301);
    }
    
    public function calendar($id, $year, $month, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Group does not exist.");
        }

        return $app->redirect("/area/".$this->parameters['location']->getAreaId()."/calendar/".$year."/".$month, 301);
    }
    
    
    public function allEvents($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }
        
        return $app->redirect("/area/".$this->parameters['location']->getAreaId()."?eventListFilterDataSubmitted=1&from=", 301);
    }
    
    
    public function groups($id, Request $request, Application $app)
    {
        if (!$this->build($id, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }
        
        return $app->redirect("/area/".$this->parameters['location']->getAreaId(), 301);
    }
}
