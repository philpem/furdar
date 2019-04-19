<?php

namespace org\openacalendar\gists\site\controllers;

use org\openacalendar\gists\models\GistContentModel;
use org\openacalendar\gists\models\GistModel;
use org\openacalendar\gists\repositories\builders\GistContentRepositoryBuilder;
use org\openacalendar\gists\repositories\GistContentRepository;
use org\openacalendar\gists\repositories\GistRepository;
use org\openacalendar\gists\site\forms\NewContentForm;
use repositories\EventRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GistEditController
{

    /** @var  GistModel */
    protected $gist;

    protected function build($slug, Request $request, Application $app)
    {
        $gistRepository = new GistRepository($app);

        // load
        $this->gist =  $gistRepository->loadBySlug($app['currentSite'], $slug);
        if (!$this->gist) {
            return false;
        }

        //  permissions
        $permission =
            $app['currentUserPermissions']->hasPermission("org.openacalendar.gists", "GISTS") &&
            $gistRepository->canUserEditGist($app['currentUser'], $this->gist);
        if (!$permission) {
            return false;
        }

        return true;
    }


    public function index($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "gist does not exist.");
        }


        $gcrb = new GistContentRepositoryBuilder($app);
        $gcrb->setGist($this->gist);

        $gistContents = array();
        foreach ($gcrb->fetchAll() as $gistContent) {
            $gistContent->loadModels($app);
            $gistContents[] = $gistContent;
        }

        return $app['twig']->render('site/gistedit/index.html.twig', array(
            'gist'=>$this->gist,
            'contents'=>$gistContents,
        ));
    }


    public function newContent($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "gist does not exist.");
        }

        $gistContentModel = new GistContentModel();
        $gistContentModel->setGistId($this->gist->getId());

        $form = $app['form.factory']->create(NewContentForm::class, $gistContentModel);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            $eventSlug = $form->get('event_slug')->getData();
            if ($eventSlug) {
                $eventRepo = new EventRepository($app);
                $event = $eventRepo->loadBySlug($app['currentSite'], $eventSlug);
                if ($event) {
                    $gistContentModel->setEventId($event->getId());
                } else {
                    print "NO EVENT";
                    die();
                    // TODO improve this error message :-/
                }
            }
            if ($form->isValid()) {
                $gistContentRepo = new GistContentRepository($app);
                $gistContentRepo->createAtEnd($gistContentModel, $this->gist, $app['currentUser']);

                return $app->redirect("/gist/".$this->gist->getSlug().'/edit');
            }
        }



        return $app['twig']->render('site/gistedit/newContent.html.twig', array(
            'gist'=>$this->gist,
            'form'=>$form->createView(),
        ));
    }
}
