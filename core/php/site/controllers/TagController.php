<?php

namespace site\controllers;

use Silex\Application;
use site\forms\TagEditForm;
use Symfony\Component\HttpFoundation\Request;
use repositories\TagRepository;
use repositories\builders\filterparams\EventFilterParams;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagController
{
    protected $parameters = array();
    
    protected function build($slug, Request $request, Application $app)
    {
        $this->parameters = array('currentUserWatchesGroup'=>false);

        if (strpos($slug, "-")) {
            $slug = substr($slug, 0, strpos($slug, "-"));
        }
        
        $tr = new TagRepository($app);
        $this->parameters['tag'] = $tr->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['tag']) {
            return false;
        }

        $app['currentUserActions']->set(
            "org.openacalendar",
            "tagEdit",
            $app['currentUserPermissions']->hasPermission("org.openacalendar", "TAGS_CHANGE")
            && !$this->parameters['tag']->getIsDeleted()
        );
        $app['currentUserActions']->set(
            "org.openacalendar",
            "tagDelete",
            $app['currentUserPermissions']->hasPermission("org.openacalendar", "TAGS_CHANGE")
            && !$this->parameters['tag']->getIsDeleted()
        );
        $app['currentUserActions']->set(
            "org.openacalendar",
            "tagUndelete",
            $app['currentUserPermissions']->hasPermission("org.openacalendar", "TAGS_CHANGE")
            && $this->parameters['tag']->getIsDeleted()
        );

        return true;
    }
    
    public function show($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Tag does not exist.");
        }
        
            
        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setTag($this->parameters['tag']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setIncludeAreaInformation(true);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setIncludeVenueInformation(true);
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setHasCountryControl($app['currentSite']->getCachedIsMultipleCountries());
        $this->parameters['eventListFilterParams']->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $app['currentSiteHasOneCountry']);
        $this->parameters['eventListFilterParams']->setHasTagControl(false);
        if ($app['currentUser']) {
            $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $this->parameters['eventListFilterParams']->set($_GET);
        
        $this->parameters['events'] = $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->fetchAll();
        
        
        return $app['twig']->render('site/tag/show.html.twig', $this->parameters);
    }


    public function edit($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Tag does not exist.");
        }


        $form = $app['form.factory']->create(TagEditForm::class, $this->parameters['tag']);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagRepository = new TagRepository($app);
                $tagRepository->edit($this->parameters['tag'], $app['currentUser']);

                return $app->redirect("/tag/".$this->parameters['tag']->getSlugForUrl());
            }
        }


        $this->parameters['form'] = $form->createView();


        return $app['twig']->render('site/tag/edit.html.twig', $this->parameters);
    }


    public function delete($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Tag does not exist.");
        }

        if ($this->parameters['tag']->getIsDeleted()) {
            die("No"); // TODO
        }

        if ($request->request->get('delete') == 'yes' && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $tagRepository = new TagRepository($app);
            $tagRepository->delete($this->parameters['tag'], $app['currentUser']);
            return $app->redirect("/tag/".$this->parameters['tag']->getSlugForUrl());
        }

        return $app['twig']->render('site/tag/delete.html.twig', $this->parameters);
    }


    public function undelete($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Tag does not exist.");
        }

        if (!$this->parameters['tag']->getIsDeleted()) {
            die("No"); // TODO
        }

        if ($request->request->get('undelete') == 'yes' && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $this->parameters['tag']->setIsDeleted(false);
            $tagRepository = new TagRepository($app);
            $tagRepository->edit($this->parameters['tag'], $app['currentUser']);
            return $app->redirect("/tag/".$this->parameters['tag']->getSlugForUrl());
        }

        return $app['twig']->render('site/tag/undelete.html.twig', $this->parameters);
    }
}
