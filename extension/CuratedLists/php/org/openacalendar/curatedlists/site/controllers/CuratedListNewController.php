<?php

namespace org\openacalendar\curatedlists\site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use org\openacalendar\curatedlists\models\CuratedListModel;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use org\openacalendar\curatedlists\site\forms\CuratedListNewForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListNewController
{
    public function newCuratedList(Request $request, Application $app)
    {
        $curatedList = new CuratedListModel();
        
        $form = $app['form.factory']->create(CuratedListNewForm::class, $curatedList);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $clRepository = new CuratedListRepository();
                $clRepository->create($curatedList, $app['currentSite'], $app['currentUser']);
                
                return $app->redirect("/curatedlist/".$curatedList->getSlug());
            }
        }
        
        
        return $app['twig']->render('site/curatedlistnew/new.html.twig', array(
                'form'=>$form->createView(),
            ));
    }
}
