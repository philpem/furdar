<?php


namespace org\openacalendar\curatedlists\site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventController extends \site\controllers\EventController
{
    public function editCuratedLists($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Event does not exist.");
        }

        if ($this->parameters['event']->getIsDeleted()) {
            die("No"); // TODO
        }
        
        $clrb = new CuratedListRepositoryBuilder($app);
        $clrb->setSite($app['currentSite']);
        $clrb->setUserCanEdit($app['currentUser']);
        $clrb->setEventInformation($this->parameters['event']);
        $clrb->setIncludeDeleted(false);
        $this->parameters['curatedListsUserCanEdit'] = $clrb->fetchAll();


        return $app['twig']->render('site/event/edit.curatedlists.html.twig', $this->parameters);
    }
}
