<?php

namespace index\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\UserAtEventRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class SiteController
{
    protected $parameters = array();
    
    
    protected function build($siteSlug, Request $request, Application $app)
    {
        $this->parameters = array('user'=>null);

        $repository = new SiteRepository($app);
        $this->parameters['site'] =  $repository->loadBySlug($siteSlug);
        if (!$this->parameters['site']) {
            return false;
        }
        
        if ($this->parameters['site']->getIsClosedBySysAdmin()) {
            return false;
        }
        
        return true;
    }
    
    
    public function eventMyAttendanceJson($siteSlug, $eventSlug, Request $request, Application $app)
    {
        if (!$this->build($siteSlug, $request, $app)) {
            $app->abort(404, "Site does not exist.");
        }
        
        $repo = new \repositories\EventRepository($app);
        $this->parameters['event'] = $repo->loadBySlug($this->parameters['site'], $eventSlug);
        
        if (!$this->parameters['event']) {
            $app->abort(404, "Event does not exist.");
        }
        
        $uaer = new UserAtEventRepository($app);
        $userAtEvent = $uaer->loadByUserAndEventOrInstanciate($app['currentUser'], $this->parameters['event']);

        $data = array();
        
        if ($request->request->get('CSFRToken') == $app['websession']->getCSFRToken() && !$this->parameters['event']->isInPast()) {
            if ($request->request->get('privacy') == 'public') {
                $userAtEvent->setIsPlanPublic(true);
            } elseif ($request->request->get('privacy') == 'private') {
                $userAtEvent->setIsPlanPublic(false);
            }

            if ($request->request->get('attending') == 'unknown') {
                $userAtEvent->setIsPlanUnknownAttending(true);
            } elseif ($request->request->get('attending') == 'no') {
                $userAtEvent->setIsPlanNotAttending(true);
            } elseif ($request->request->get('attending') == 'maybe') {
                $userAtEvent->setIsPlanMaybeAttending(true);
            } elseif ($request->request->get('attending') == 'yes') {
                $userAtEvent->setIsPlanAttending(true);
            }
            
            $uaer->save($userAtEvent);
        }

        if ($userAtEvent->getIsPlanAttending()) {
            $data['attending'] = 'yes';
        } elseif ($userAtEvent->getIsPlanMaybeAttending()) {
            $data['attending'] = 'maybe';
        } elseif ($userAtEvent->getIsPlanNotAttending()) {
            $data['attending'] = 'no';
        } else {
            $data['attending'] = 'unknown';
        }
        $data['privacy'] = ($userAtEvent->getIsPlanPublic() ? 'public' : 'private');
        $data['inPast'] = $this->parameters['event']->isInPast() ? 1 : 0;
        $data['CSFRToken'] = $app['websession']->getCSFRToken();
        
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
