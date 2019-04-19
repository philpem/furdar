<?php

namespace siteapi1\controllers;

use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IndexController
{
    public function index(Application $app)
    {
        return $app['twig']->render('siteapi1/index/index.html.twig', array(
            ));
    }
}
