<?php

namespace org\openacalendar\curatedlists\site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use org\openacalendar\curatedlists\models\CuratedListModel;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use repositories\builders\filterparams\EventFilterParams;
use site\forms\CuratedListEditForm;
use repositories\UserAccountRepository;
use repositories\EventRepository;
use repositories\builders\UserAccountRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListEventController
{
    protected $parameters = array();
    
    protected function build($slug, $eslug, Request $request, Application $app)
    {
        $this->parameters = array();

        $curatedlistRepository = new CuratedListRepository();
        $this->parameters['curatedlist'] =  $curatedlistRepository->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['curatedlist']) {
            return false;
        }
        
        $eventRepository = new EventRepository($app);
        $this->parameters['event'] =  $eventRepository->loadBySlug($app['currentSite'], $eslug);
        if (!$this->parameters['event']) {
            return false;
        }
        
        $this->parameters['currentUserCanEditCuratedList'] = $this->parameters['curatedlist']->canUserEdit($app['currentUser']);
        
        return true;
    }
    
    public function remove($slug, $eslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $eslug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }
        
        if ($this->parameters['currentUserCanEditCuratedList'] && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $curatedlistRepository = new CuratedListRepository();
            $curatedlistRepository->removeEventFromCuratedList($this->parameters['event'], $this->parameters['curatedlist'], $app['currentUser']);
        }
        
        if ($request->request->get('returnTo', 'event') == 'event') {
            return $app->redirect("/event/".$this->parameters['event']->getSlugForURL());
        } elseif ($request->request->get('returnTo', 'event') == 'curatedlist') {
            return $app->redirect("/curatedlist/".$this->parameters['curatedlist']->getSlug());
        }
    }
    
    public function add($slug, $eslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $eslug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }
        
        if ($this->parameters['currentUserCanEditCuratedList'] && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $curatedlistRepository = new CuratedListRepository();
            $curatedlistRepository->addEventtoCuratedList($this->parameters['event'], $this->parameters['curatedlist'], $app['currentUser']);
        }
        
        if ($request->request->get('returnTo', 'event') == 'event') {
            return $app->redirect("/event/".$this->parameters['event']->getSlugForURL());
        } elseif ($request->request->get('returnTo', 'event') == 'curatedlist') {
            return $app->redirect("/curatedlist/".$this->parameters['curatedlist']->getSlug());
        }
    }
}
