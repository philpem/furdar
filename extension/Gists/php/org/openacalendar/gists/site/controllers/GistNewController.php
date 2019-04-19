<?php

namespace org\openacalendar\gists\site\controllers;

use org\openacalendar\gists\models\GistModel;
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
class GistNewController
{
    protected function build(Request $request, Application $app)
    {
        //  permissions
        $permission =
            $app['currentUserPermissions']->hasPermission("org.openacalendar.gists", "GISTS");


        if (!$permission) {
            return false;
        }

        return true;
    }

    public function index(Request $request, Application $app)
    {
        if (!$this->build($request, $app)) {
            $app->abort(404, "not.");
        }

        if ($request->isMethod('POST') && $request->request->get('action') == 'new') {
            $gistModel = new GistModel();
            $gistModel->setTitle('Welcome'); // TODO remove hard coding

            $gistRepository = new GistRepository($app);
            $gistRepository->create($gistModel, $app['currentSite'], $app['currentUser']);

            return $app->redirect("/gist/".$gistModel->getSlug().'/edit');
        }

        return $app['twig']->render('site/gistnew/index.html.twig');
    }
}
