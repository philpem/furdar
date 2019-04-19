<?php

namespace site\controllers;

use repositories\builders\filterparams\ImportedEventFilterParams;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportImportedEventListController extends ImportController
{
    public function index($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Import does not exist.");
        }


        $this->parameters['importedEventListFilterParams'] = new ImportedEventFilterParams($app);
        $this->parameters['importedEventListFilterParams']->set($_GET);
        $this->parameters['importedEventListFilterParams']->getImportedEventRepositoryBuilder()->setSite($app['currentSite']);
        $this->parameters['importedEventListFilterParams']->getImportedEventRepositoryBuilder()->setImport($this->parameters['import']);

        $this->parameters['importedEvents'] = $this->parameters['importedEventListFilterParams']->getImportedEventRepositoryBuilder()->fetchAll();

        return $app['twig']->render('site/importimportedeventlist/index.html.twig', $this->parameters);
    }
}
