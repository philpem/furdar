<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\MediaRepository;
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
class MediaController
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
        
        $mr = new MediaRepository($app);
        $this->parameters['media'] = $mr->loadBySlug($this->parameters['site'], $slug);
        if (!$this->parameters['media']) {
            $app->abort(404);
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
                    $scr->createAboutMedia($this->parameters['media'], $data['comment'], $app['currentUser']);
                    $redirect = true;
                }


                if ($action->getCommand() == 'delete' && !$this->parameters['media']->getIsDeleted()) {
                    $mr = new MediaRepository($app);
                    $mr->delete($this->parameters['media'], $app['currentUser']);
                    $redirect = true;
                }

                if ($redirect) {
                    return $app->redirect('/sysadmin/site/'.$this->parameters['site']->getId().'/media/'.$this->parameters['media']->getSlug());
                }
            }
        }
        
        $this->parameters['form'] = $form->createView();


        $sacrb = new SysadminCommentRepositoryBuilder($app);
        $sacrb->setMedia($this->parameters['media']);
        $this->parameters['comments'] = $sacrb->fetchAll();
            
        
        return $app['twig']->render('sysadmin/media/index.html.twig', $this->parameters);
    }
}
