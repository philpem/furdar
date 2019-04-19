<?php

namespace sysadmin\controllers;

use repositories\builders\NewEventDraftRepositoryBuilder;
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
class NewEventDraftListController
{
    public function listForSite($siteid, Request $request, Application $app)
    {
        $sr = new SiteRepository($app);
        $site = $sr->loadById($siteid);

        if (!$site) {
            die("404");
        }

        $nedrb = new NewEventDraftRepositoryBuilder($app);
        $nedrb->setSite($site);
        $drafts = $nedrb->fetchAll();

        return $app['twig']->render('sysadmin/neweventdraftlist/index.html.twig', array(
            'site'=>$site,
            'neweventdrafts'=>$drafts,
        ));
    }
}
