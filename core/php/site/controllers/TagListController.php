<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\builders\TagRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagListController
{
    public function index(Application $app)
    {
        $trb = new TagRepositoryBuilder($app);
        $trb->setSite($app['currentSite']);
        $trb->setIncludeDeleted(false);
        $tags = $trb->fetchAll();
        
        return $app['twig']->render('site/taglist/index.html.twig', array(
                'tags'=>$tags,
            ));
    }
}
