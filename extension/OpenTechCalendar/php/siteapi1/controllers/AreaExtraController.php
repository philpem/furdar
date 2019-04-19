<?php

namespace siteapi1\controllers;

use repositories\builders\EventRepositoryBuilder;
use repositories\builders\GroupRepositoryBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AreaExtraController extends AreaController
{
    public function groupsJson($slug, Request $request, Application $application)
    {
        if (! $this->build($slug, $request, $application)) {
            $application->abort(404, "Area does not exist.");
        }

        $erb = new EventRepositoryBuilder($application);
        $erb->setAfterNow();
        $erb->setArea($this->parameters['area']);
        $erb->setLimit(500);

        $groups = array();
        foreach ($erb->fetchAll() as $event) {
            $grb = new GroupRepositoryBuilder($application);
            $grb->setEvent($event);
            foreach ($grb->fetchAll() as $group) {
                $groups[$group->getSlug()]  = array(
                    'slug' => $group->getSlug(),
                    'title' => $group->getTitle(),
                    'slugForURL' => $group->getSlugForURL(),
                );
            }
        }

        $response = new Response(json_encode(array('data'=> array_values($groups))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
