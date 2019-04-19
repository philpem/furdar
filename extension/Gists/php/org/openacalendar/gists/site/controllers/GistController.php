<?php

namespace org\openacalendar\gists\site\controllers;

use org\openacalendar\gists\models\GistModel;
use org\openacalendar\gists\repositories\builders\GistContentRepositoryBuilder;
use org\openacalendar\gists\repositories\GistRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GistController
{

    /** @var  GistModel */
    protected $gist;

    protected function build($slug, Request $request, Application $app)
    {
        $gistRepository = new GistRepository($app);
        $this->gist =  $gistRepository->loadBySlug($app['currentSite'], $slug);
        if (!$this->gist) {
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


        return $app['twig']->render('site/gist/index.html.twig', array(
            'gist'=>$this->gist,
            'contents'=>$gistContents,
        ));
    }
}
