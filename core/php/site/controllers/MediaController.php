<?php

namespace site\controllers;

use models\MediaEditMetaDataModel;
use repositories\builders\HistoryRepositoryBuilder;
use Silex\Application;
use site\forms\MediaEditForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\GroupModel;
use models\MediaModel;
use repositories\MediaRepository;
use repositories\SiteProfileMediaRepository;

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
    
    protected function build($slug, Request $request, Application $app)
    {
        $this->parameters = array();
        
        $mr = new MediaRepository($app);
        $this->parameters['media'] = $mr->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['media']) {
            return false;
        }
        
        if ($this->parameters['media']->getIsDeleted()) {
            return false;
        }

        $app['currentUserActions']->set("org.openacalendar", "mediaHistory", true);
        $app['currentUserActions']->set(
            "org.openacalendar",
            "mediaEditDetails",
            $app['currentUserPermissions']->hasPermission("org.openacalendar", "MEDIAS_CHANGE")
            && !$this->parameters['media']->getIsDeleted()
        );

        return true;
    }
    
    public function show($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Media does not exist.");
        }
        
        if ($request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            if ($request->request->get('action') == 'makeSiteLogo' && $app['currentUserPermissions']->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE")) {
                $app['currentSite']->setLogoMediaId($this->parameters['media']->getId());
                $siteProfileMediaRepository = new SiteProfileMediaRepository($app);
                $siteProfileMediaRepository->createOrEdit($app['currentSite'], $app['currentUser']);
                $app['flashmessages']->addMessage("Saved.");
                return $app->redirect("/media/".$this->parameters['media']->getSlug());
            }
        }
        
        
        return $app['twig']->render('site/media/show.html.twig', $this->parameters);
    }

    public function history($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Media does not exist.");
        }


        $historyRepositoryBuilder = new HistoryRepositoryBuilder($app);
        $historyRepositoryBuilder->getHistoryRepositoryBuilderConfig()->setMedia($this->parameters['media']);
        $this->parameters['historyItems'] = $historyRepositoryBuilder->fetchAll();

        return $app['twig']->render('site/media/history.html.twig', $this->parameters);
    }


    public function imageThumbnail($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Media does not exist.");
        }
        
        return $this->parameters['media']->getThumbnailResponse($app['config']->mediaBrowserCacheExpiresInseconds);
    }
    
    public function imageNormal($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Media does not exist.");
        }
        
        return $this->parameters['media']->getNormalResponse($app['config']->mediaBrowserCacheExpiresInseconds);
    }
    
    public function imageFull($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Media does not exist.");
        }
        
        return $this->parameters['media']->getResponse($app['config']->mediaBrowserCacheExpiresInseconds);
    }

    public function editDetails($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Media does not exist.");
        }

        if ($this->parameters['media']->getIsDeleted()) {
            die("No"); // TODO
        }

        $form = $app['form.factory']->create(MediaEditForm::class, $this->parameters['media']);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $mediaEditMetaDataModel = new MediaEditMetaDataModel();
                $mediaEditMetaDataModel->setUserAccount($app['currentUser']);
                $mediaEditMetaDataModel->setFromRequest($request);

                $mediaRepository = new MediaRepository($app);
                $mediaRepository->editWithMetaData($this->parameters['media'], $mediaEditMetaDataModel);

                return $app->redirect("/media/".$this->parameters['media']->getSlugForUrl());
            }
        }


        $this->parameters['form'] = $form->createView();
        return $app['twig']->render('site/media/edit.details.html.twig', $this->parameters);
    }
}
