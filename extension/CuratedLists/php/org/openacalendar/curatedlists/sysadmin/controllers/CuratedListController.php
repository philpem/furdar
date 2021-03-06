<?php

namespace org\openacalendar\curatedlists\sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\UserAccountRepository;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use repositories\EventRepository;
use sysadmin\forms\ActionForm;
use sysadmin\ActionParser;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListController
{
    protected $parameters = array();
    
    protected function build($siteid, $slug, Request $request, Application $app)
    {
        $this->parameters = array('group'=>null);

        $sr = new SiteRepository($app);
        $this->parameters['site'] = $sr->loadById($siteid);
        
        if (!$this->parameters['site']) {
            $app->abort(404);
        }
        
        $clr = new CuratedListRepository();
        $this->parameters['curatedlist'] = $clr->loadBySlug($this->parameters['site'], $slug);
        
        if (!$this->parameters['curatedlist']) {
            $app->abort(404);
        }
    }
    
    public function index($siteid, $slug, Request $request, Application $app)
    {
        global $CONFIG;

        $this->build($siteid, $slug, $request, $app);
        
                
        $form = $app['form.factory']->create(ActionForm::class);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $action = new ActionParser($data['action']);

                if ($action->getCommand() == 'delete' && !$this->parameters['curatedlist']->getIsDeleted()) {
                    $clr = new CuratedListRepository();
                    $clr->delete($this->parameters['curatedlist'], $app['currentUser']);
                    return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/curatedlist/'.$this->parameters['curatedlist']->getSlug());
                } elseif ($action->getCommand() == 'undelete' && $this->parameters['curatedlist']->getIsDeleted()) {
                    $clr = new CuratedListRepository();
                    $clr->undelete($this->parameters['curatedlist'], $app['currentUser']);
                    return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/curatedlist/'.$this->parameters['curatedlist']->getSlug());
                } elseif ($action->getCommand() == 'addeditor') {
                    $userRepo = new UserAccountRepository($app);
                    $user = $userRepo->loadByID($action->getParam(0));
                    if ($user) {
                        $clr = new CuratedListRepository();
                        $clr->addEditorToCuratedList($user, $this->parameters['curatedlist'], $app['currentUser']);
                        return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/curatedlist/'.$this->parameters['curatedlist']->getSlug());
                    }
                } elseif ($action->getCommand() == 'removeeditor') {
                    $userRepo = new UserAccountRepository($app);
                    $user = $userRepo->loadByID($action->getParam(0));
                    if ($user) {
                        $clr = new CuratedListRepository();
                        $clr->removeEditorFromCuratedList($user, $this->parameters['curatedlist'], $app['currentUser']);
                        return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/curatedlist/'.$this->parameters['curatedlist']->getSlug());
                    }
                } elseif ($action->getCommand() == 'addevent') {
                    $eventRepository = new EventRepository($app);
                    $event = $eventRepository->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($event) {
                        $clr = new CuratedListRepository();
                        $clr->addEventtoCuratedList($event, $this->parameters['curatedlist'], $app['currentUser']);
                        return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/event/'.$event->getSlug());
                    }
                } elseif ($action->getCommand() == 'removeevent') {
                    $eventRepository = new EventRepository($app);
                    $event = $eventRepository->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($event) {
                        $clr = new CuratedListRepository();
                        $clr->removeEventFromCuratedList($event, $this->parameters['curatedlist'], $app['currentUser']);
                        return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/event/'.$event->getSlug());
                    }
                } elseif ($action->getCommand() == 'purge' && $CONFIG->sysAdminExtraPurgeCuratedListPassword && $CONFIG->sysAdminExtraPurgeCuratedListPassword == $action->getParam(0)) {
                    $clr = new CuratedListRepository();
                    $clr->purge($this->parameters['curatedlist']);
                    return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/curatedlist/');
                }
            }
        }
        
        $this->parameters['form'] = $form->createView();
            
        
        
        return $app['twig']->render('sysadmin/curatedlist/index.html.twig', $this->parameters);
    }
}
