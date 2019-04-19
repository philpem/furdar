<?php

namespace site\controllers;

use models\TagEditMetaDataModel;
use Silex\Application;
use site\forms\TagNewForm;
use site\forms\TagEditForm;
use site\forms\EventNewForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\TagModel;
use models\EventModel;
use repositories\TagRepository;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagNewController
{
    public function newTag(Request $request, Application $app)
    {
        $tag = new TagModel();
        
        $form = $app['form.factory']->create(TagNewForm::class, $tag);



        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagEditMetaDataModel = new TagEditMetaDataModel();
                $tagEditMetaDataModel->setUserAccount($app['currentUser']);
                $tagEditMetaDataModel->setFromRequest($request);

                $tagRepository = new TagRepository($app);
                $tagRepository->createWithMetaData($tag, $app['currentSite'], $tagEditMetaDataModel);
                
                return $app->redirect("/tag/".$tag->getSlugForUrl());
            }
        }
        
        
        return $app['twig']->render('site/tagnew/new.html.twig', array(
                'form'=>$form->createView(),
            ));
    }
}
