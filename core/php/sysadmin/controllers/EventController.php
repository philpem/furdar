<?php

namespace sysadmin\controllers;

use models\EventEditMetaDataModel;
use repositories\AreaRepository;
use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\SiteRepository;
use repositories\EventRepository;
use repositories\GroupRepository;
use repositories\CountryRepository;
use repositories\VenueRepository;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use repositories\builders\SiteRepositoryBuilder;
use repositories\builders\GroupRepositoryBuilder;
use repositories\builders\UserAccountRepositoryBuilder;
use sysadmin\forms\ActionWithCommentForm;
use sysadmin\ActionParser;
use repositories\builders\SysadminCommentRepositoryBuilder;
use repositories\SysAdminCommentRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventController
{
    protected $parameters = array();
    
    protected function build($siteid, $slug, Request $request, Application $app)
    {
        $this->parameters = array('group'=>null,'venue'=>null,'country'=>null,'area'=>null);

        $sr = new SiteRepository($app);
        $this->parameters['site'] = $sr->loadById($siteid);
        
        if (!$this->parameters['site']) {
            $app->abort(404);
        }

        $er = new EventRepository($app);
        $this->parameters['event'] = $er->loadBySlug($this->parameters['site'], $slug);

        $this->parameters['eventisduplicateof'] = $this->parameters['event']->getIsDuplicateOfId() ? $er->loadById($this->parameters['event']->getIsDuplicateOfId()) : null;

        if (!$this->parameters['event']) {
            $app->abort(404);
        }
    
        if ($this->parameters['event']->getGroupId()) {
            $gr = new GroupRepository($app);
            $this->parameters['group'] = $gr->loadById($this->parameters['event']->getGroupId());
        }
        
        if ($this->parameters['event']->getCountryID()) {
            $cr = new CountryRepository($app);
            $this->parameters['country'] = $cr->loadById($this->parameters['event']->getCountryID());
        }
        
        if ($this->parameters['event']->getVenueID()) {
            $cr = new VenueRepository($app);
            $this->parameters['venue'] = $cr->loadById($this->parameters['event']->getVenueID());
        }


        if ($this->parameters['event']->getAreaID()) {
            $ar = new AreaRepository($app);
            $this->parameters['area'] = $ar->loadById($this->parameters['event']->getAreaID());
        }
    }
    
    public function index($siteid, $slug, Request $request, Application $app)
    {
        $this->build($siteid, $slug, $request, $app);

            
        $form = $app['form.factory']->create(ActionWithCommentForm::class);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            
            
            if ($form->isValid()) {
                $data = $form->getData();
                $action = new ActionParser($data['action']);


                $redirect = false;

                if ($data['comment']) {
                    $scr = new SysAdminCommentRepository($app);
                    $scr->createAboutEvent($this->parameters['event'], $data['comment'], $app['currentUser']);
                    $redirect = true;
                }


                if ($action->getCommand() == 'addgroup') {
                    $gr = new GroupRepository($app);
                    $group = $gr->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($group) {
                        $gr->addEventToGroup($this->parameters['event'], $group, $app['currentUser']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'removegroup') {
                    $gr = new GroupRepository($app);
                    $group = $gr->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($group) {
                        $gr->removeEventFromGroup($this->parameters['event'], $group, $app['currentUser']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'maingroup') {
                    $gr = new GroupRepository($app);
                    $group = $gr->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($group) {
                        $gr->setMainGroupForEvent($group, $this->parameters['event'], $app['currentUser']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'delete' && !$this->parameters['event']->getIsDeleted()) {
                    $er = new EventRepository($app);
                    $er->delete($this->parameters['event'], $app['currentUser']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'undelete' && $this->parameters['event']->getIsDeleted()) {
                    $this->parameters['event']->setIsDeleted(false);
                    $er = new EventRepository($app);
                    $er->undelete($this->parameters['event'], $app['currentUser']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'cancel' && !$this->parameters['event']->getIsDeleted()) {
                    $er = new EventRepository($app);
                    $er->cancel($this->parameters['event'], $app['currentUser']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'uncancel' && $this->parameters['event']->getIsCancelled()) {
                    $er = new EventRepository($app);
                    $er->uncancel($this->parameters['event'], $app['currentUser']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'addcuratedlist') {
                    $clr = new CuratedListRepository();
                    $curatedList = $clr->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($curatedList) {
                        $clr->addEventtoCuratedList($this->parameters['event'], $curatedList, $app['currentUser']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'removecuratedlist') {
                    $clr = new CuratedListRepository();
                    $curatedList = $clr->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($curatedList) {
                        $clr->removeEventFromCuratedList($this->parameters['event'], $curatedList, $app['currentUser']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'isduplicateof') {
                    $er = new EventRepository($app);
                    $originalEvent = $er->loadBySlug($this->parameters['site'], $action->getParam(0));
                    if ($originalEvent && $originalEvent->getId() != $this->parameters['event']->getId()) {
                        $er->markDuplicate($this->parameters['event'], $originalEvent, $app['currentUser']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'isnotduplicate') {
                    $er = new EventRepository($app);
                    $eventEditMetaData = new EventEditMetaDataModel();
                    $eventEditMetaData->setUserAccount($app['currentUser']);
                    $eventEditMetaData->setFromRequest($request);
                    $er->markNotDuplicateWithMetaData($this->parameters['event'], $eventEditMetaData);
                    $redirect = true;
                } elseif ($action->getCommand() == 'slughuman') {
                    $this->parameters['event']->setSlugHuman($action->getParam(0));
                    $er = new EventRepository($app);
                    $er->editSlugHuman($this->parameters['event']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'purge' && $app['config']->sysAdminExtraPurgeEventPassword && $app['config']->sysAdminExtraPurgeEventPassword == $action->getParam(0)) {
                    $er = new EventRepository($app);
                    $er->purge($this->parameters['event']);
                    return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/event/');
                }

                if ($redirect) {
                    return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/event/'.$this->parameters['event']->getSlug());
                }
            }
        }
        
        $groupRB = new GroupRepositoryBuilder($app);
        $groupRB->setEvent($this->parameters['event']);
        $this->parameters['groups'] = $groupRB->fetchAll();
        
        $this->parameters['form'] = $form->createView();

        $sacrb = new SysadminCommentRepositoryBuilder($app);
        $sacrb->setEvent($this->parameters['event']);
        $this->parameters['comments'] = $sacrb->fetchAll();
        
        return $app['twig']->render('sysadmin/event/index.html.twig', $this->parameters);
    }
}
