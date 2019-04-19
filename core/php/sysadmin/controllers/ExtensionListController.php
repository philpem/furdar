<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionListController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/extensionlist/index.html.twig', array(
                'extensions'=>$app['extensions']->getExtensionsIncludingCore(),
            ));
    }
}
