<?php

namespace sysadmin\controllers;

use models\EventCustomFieldDefinitionModel;
use models\EventEditMetaDataModel;
use repositories\builders\EventCustomFieldDefinitionRepositoryBuilder;
use repositories\EventCustomFieldDefinitionRepository;
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
use sysadmin\forms\ActionForm;
use sysadmin\ActionParser;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventCustomFieldDefinitionListController
{
    protected $parameters = array();

    protected function build($siteid, Request $request, Application $app)
    {
        $this->parameters = array();

        $sr = new SiteRepository($app);
        $this->parameters['site'] = $sr->loadById($siteid);

        if (!$this->parameters['site']) {
            $app->abort(404);
        }
    }

    public function index($siteid, Request $request, Application $app)
    {
        $this->build($siteid, $request, $app);


        $form = $app['form.factory']->create(ActionForm::class);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);


            if ($form->isValid()) {
                $data = $form->getData();
                $action = new ActionParser($data['action']);

                if ($action->getCommand() == 'addtextsingleline') {
                    $ecfdm = new EventCustomFieldDefinitionModel();
                    if ($ecfdm->isKeyValid($action->getParam(0))) {
                        $ecfdm->setSiteId($this->parameters['site']->getId());
                        $ecfdm->setExtensionId('org.openacalendar');
                        $ecfdm->setType('TextSingleLine');
                        $ecfdm->setKey($action->getParam(0));
                        $ecfdm->setLabel($action->getParam(0));
                        $repo = new EventCustomFieldDefinitionRepository($app);
                        $repo->create($ecfdm, $app['currentUser']);
                        return $app->redirect('/sysadmin/site/' . $this->parameters['site']->getId() . '/eventcustomfielddefinition/' . $ecfdm->getId());
                    } else {
                        $app['flashmessages']->addError("Key Not Allowed");
                    }
                } elseif ($action->getCommand() == 'addtextmultiline') {
                    $ecfdm = new EventCustomFieldDefinitionModel();
                    if ($ecfdm->isKeyValid($action->getParam(0))) {
                        $ecfdm->setSiteId($this->parameters['site']->getId());
                        $ecfdm->setExtensionId('org.openacalendar');
                        $ecfdm->setType('TextMultiLine');
                        $ecfdm->setKey($action->getParam(0));
                        $ecfdm->setLabel($action->getParam(0));
                        $repo = new EventCustomFieldDefinitionRepository($app);
                        $repo->create($ecfdm, $app['currentUser']);
                        return $app->redirect('/sysadmin/site/' . $this->parameters['site']->getId() . '/eventcustomfielddefinition/' . $ecfdm->getId());
                    } else {
                        $app['flashmessages']->addError("Key Not Allowed");
                    }
                }
            }
        }

        $this->parameters['form'] = $form->createView();



        $rb = new EventCustomFieldDefinitionRepositoryBuilder($app);
        $rb->setSite($this->parameters['site']);
        $this->parameters['fields'] = $rb->fetchAll();

        return $app['twig']->render('sysadmin/eventcustomfielddefinitionlist/index.html.twig', $this->parameters);
    }
}
