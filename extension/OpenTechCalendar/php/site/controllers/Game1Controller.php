<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\builders\EventRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class Game1Controller
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('site/game1/index.html.twig', array(
        ));
    }

    
    public function dataJson(Request $request, Application $app)
    {
        $data = array('squares'=>array());
        
        // build base data
        $start = \TimeSource::getDateTime();
        $start->setTime(0, 0, 0);
        $oneDay = new \DateInterval('P1D');
        $today = clone  $start;
        for ($week = 1; $week <= 7; $week++) {
            $data['squares'][$week] = array();
            for ($day = 1; $day <= 7; $day++) {
                $endToday = clone $today;
                $endToday->setTime(23, 59, 59);
                
                $data['squares'][$week][$day] = array(
                    'events' => array(),
                    'startTimeStamp' => $today->getTimestamp(),
                    'endTimeStamp' => $endToday->getTimestamp(),
                    'date'=>$today->format('D jS M Y'),
                );
                
                $today->add($oneDay);
            }
        }
        
        // add events
        $eventRepositoryBuilder = new EventRepositoryBuilder($app);
        $eventRepositoryBuilder->setAfter($start);
        $eventRepositoryBuilder->setBefore($today);
        $eventRepositoryBuilder->setIncludeDeleted(false);
        
        $events = $eventRepositoryBuilder->fetchAll();
        
        foreach ($events as $event) {
            for ($week = 1; $week <= 7; $week++) {
                for ($day = 1; $day <= 7; $day++) {
                    $startAt = $event->getStartAt()->getTimestamp();
                    $d = $data['squares'][$week][$day];
                
                    if ($d['startTimeStamp'] <= $startAt && $startAt <= $d['endTimeStamp']) {
                        $data['squares'][$week][$day]['events'][] = array(
                            'slug'=>$event->getSlug(),
                            'summary'=>$event->getSummaryDisplay(),
                        );
                    }
                }
            }
        }
        
        return json_encode($data);
    }
}
