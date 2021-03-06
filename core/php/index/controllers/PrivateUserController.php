<?php

namespace index\controllers;

use api1exportbuilders\ICalEventIdConfig;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use repositories\UserAccountRepository;
use repositories\UserAccountPrivateFeedKeyRepository;
use Symfony\Component\Form\FormError;
use api1exportbuilders\EventListICalBuilder;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class PrivateUserController
{
    protected $parameters = array();
    
    
    protected function build($username, $accesskey, Request $request, Application $app)
    {
        $this->parameters = array('user'=>null);

        $repository = new UserAccountRepository($app);
        $this->parameters['user'] =  $repository->loadByUserName($username);
        if (!$this->parameters['user']) {
            return false;
        }
        
        if ($this->parameters['user']->getIsClosedBySysAdmin()) {
            return false;
        }
        
        $repository = new UserAccountPrivateFeedKeyRepository($app);
        $this->parameters['feedKey'] =  $repository->loadByUserAccountIDAndAccessKey($this->parameters['user']->getId(), $accesskey);
        if (!$this->parameters['feedKey']) {
            return false;
        }

        $repository->editLastUsed($this->parameters['feedKey']);
        
        return true;
    }
    
    
    public function icalAttendingWatching($username, $accesskey, Request $request, Application $app)
    {
        if (!$this->build($username, $accesskey, $request, $app)) {
            $app->abort(404, "User does not exist.");
        }
                
        // TODO should we be passing a better timeZone here?
        $ical = new EventListICalBuilder($app, null, "UTC", $this->parameters['user']->getUserName(), new ICalEventIdConfig($request->get('eventidconfig'), $request->server->all()));
        $ical->getEventRepositoryBuilder()->setUserAccount($this->parameters['user'], false, true, true, true);
        $ical->build();
        return $ical->getResponse();
    }
    
    public function icalAttending($username, $accesskey, Request $request, Application $app)
    {
        if (!$this->build($username, $accesskey, $request, $app)) {
            $app->abort(404, "User does not exist.");
        }
                
        // TODO should we be passing a better timeZone here?
        $ical = new EventListICalBuilder($app, null, "UTC", $this->parameters['user']->getUserName(), new ICalEventIdConfig($request->get('eventidconfig'), $request->server->all()));
        $ical->getEventRepositoryBuilder()->setUserAccount($this->parameters['user'], false, true, true, false);
        $ical->build();
        return $ical->getResponse();
    }
}
