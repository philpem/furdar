<?php

namespace siteapi1\controllers;

use api1exportbuilders\EventListJSONBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\AreaRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupAreaExtraController extends GroupController
{
    protected function buildArea($slug, Request $request, Application $app)
    {
        if (strpos($slug, "-") > 0) {
            $slugBits = explode("-", $slug, 2);
            $slug = $slugBits[0];
        }

        $ar = new AreaRepository($app);
        $this->parameters['area'] = $ar->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['area']) {
            return false;
        }
        
        return true;
    }


    public function eventsForScheduleJson($groupSlug, $areaSlug, Request $request, Application $application)
    {
        if (! $this->build($groupSlug, $request, $application)) {
            $application->abort(404, "Group does not exist.");
        }
        if (! $this->buildArea($areaSlug, $request, $application)) {
            $application->abort(404, "Area does not exist.");
        }

        $json = new EventListJSONBuilder($application, $application['currentSite'], $application['currentTimeZone']);
        $after = new \DateTime();
        $after->sub(new \DateInterval('P6M'));
        $json->getEventRepositoryBuilder()->setAfter($after);
        $json->getEventRepositoryBuilder()->setLimit(500);
        $json->setIncludeEventMedias(false);
        $json->getEventRepositoryBuilder()->setArea($this->parameters['area']);
        $json->getEventRepositoryBuilder()->setGroup($this->parameters['group']);
        $json->build();
        return $json->getResponse();
    }
}
