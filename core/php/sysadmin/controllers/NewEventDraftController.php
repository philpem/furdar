<?php

namespace sysadmin\controllers;

use models\UserAccountModel;
use repositories\builders\NewEventDraftRepositoryBuilder;
use repositories\EventRepository;
use repositories\NewEventDraftRepository;
use repositories\UserAccountRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\builders\MediaRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewEventDraftController
{
    protected $parameters = array();

    protected function build($siteid, $slug, Request $request, Application $app)
    {
        $this->parameters = array('user'=>null,'eventCreated'=>null, 'eventDupe'=>null);

        $sr = new SiteRepository($app);
        $this->parameters['site'] = $sr->loadById($siteid);

        if (!$this->parameters['site']) {
            $app->abort(404);
        }

        $repo = new NewEventDraftRepository($app);
        $this->parameters['draft'] = $repo->loadBySlugForSite($slug, $this->parameters['site']);

        if (!$this->parameters['draft']) {
            $app->abort(404);
        }

        if ($this->parameters['draft']->getUserAccountId()) {
            $ur = new UserAccountRepository($app);
            $this->parameters['user'] = $ur->loadByID($this->parameters['draft']->getUserAccountId());
        }

        if ($this->parameters['draft']->getEventId()) {
            $er = new EventRepository($app);
            $this->parameters['eventCreated'] = $er->loadByID($this->parameters['draft']->getEventId());
        }

        if ($this->parameters['draft']->getWasExistingEventId()) {
            $er = new EventRepository($app);
            $this->parameters['eventDupe'] = $er->loadByID($this->parameters['draft']->getWasExistingEventId());
        }
    }

    public function show($siteid, $slug, Request $request, Application $app)
    {
        $this->build($siteid, $slug, $request, $app);

        return $app['twig']->render('sysadmin/neweventdraft/index.html.twig', $this->parameters);
    }
}
