<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use repositories\CountryRepository;
use repositories\AreaRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class OpenTechCalendarController
{
    public function userEditable(Application $app)
    {
        return $app['twig']->render('site/index/usereditable.html.twig', array(
            ));
    }

    public function frontPageForArea($slug, Application $app)
    {
        $slugBits = explode("-", $slug, 2);

        $ar = new AreaRepository($app);
        $area= $ar->loadBySlug($app['currentSite'], $slugBits[0]);
        if (!$area) {
            return false;
        }


        $cal = new \RenderCalendar($app);
        $cal->getEventRepositoryBuilder()->setSite($app['currentSite']);
        $cal->getEventRepositoryBuilder()->setIncludeDeleted(false);
        $cal->getEventRepositoryBuilder()->setArea($area);
        $cal->byDate(\TimeSource::getDateTime(), 4*7+1, true);

        $calData = $cal->getData();

        $out = array();
        foreach ($calData as $daysData) {
            $out[] = array(
                'count'=>count($daysData['events']) + count($daysData['eventsContinuing']),
            );
        }

        $response = new Response(json_encode(array('data'=>$out)));
        $response->headers->set('Content-Type', 'application/json');
        $response->setPublic();
        $response->setMaxAge(60*60*24);
        return $response;
    }


    public function frontPageForCountry($code, Application $app)
    {
        $gr = new CountryRepository($app);
        $country =  $gr->loadByTwoCharCode($code);
        if (!$country) {
            return false;
        }

        $cal = new \RenderCalendar($app);
        $cal->getEventRepositoryBuilder()->setSite($app['currentSite']);
        $cal->getEventRepositoryBuilder()->setIncludeDeleted(false);
        $cal->getEventRepositoryBuilder()->setCountry($country);
        $cal->byDate(\TimeSource::getDateTime(), 4*7+1, true);

        $calData = $cal->getData();

        $out = array();
        foreach ($calData as $daysData) {
            $out[] = array(
                'count'=>count($daysData['events']) + count($daysData['eventsContinuing']),
            );
        }

        $response = new Response(json_encode(array('data'=>$out)));
        $response->headers->set('Content-Type', 'application/json');
        $response->setPublic();
        $response->setMaxAge(60*60*24);
        return $response;
    }
}
