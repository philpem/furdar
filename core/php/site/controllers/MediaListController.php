<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\MediaModel;
use repositories\MediaRepository;
use repositories\builders\MediaRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MediaListController
{
    public function index(Application $app)
    {
        $mrb = new MediaRepositoryBuilder($app);
        $mrb->setIncludeDeleted(false);
        $mrb->setSite($app['currentSite']);
        $medias = $mrb->fetchAll();
        
        return $app['twig']->render('site/medialist/index.html.twig', array(
                'medias'=>$medias,
            ));
    }
}
