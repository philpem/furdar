<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\CountryRepository;
use repositories\LegacyRegionRepository;
use repositories\builders\LegacyLocationRepositoryBuilder;

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
        
        
        $rr = new LegacyRegionRepository;
        $this->parameters['region'] = $rr->loadById($regionslug);
        if (!$this->parameters['region']) {
            return false;
        }
        
        
        return true;
    }
    
    public function show($slug, $regionslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $regionslug, $request, $app)) {
            $app->abort(404, "Country Region does not exist.");
        }
        
        return $app->redirect("/area/".$this->parameters['region']->getAreaId(), 301);
    }
}
