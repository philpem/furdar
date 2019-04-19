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
class ExtensionController
{
    protected $parameters = array();

    protected function build($id, Request $request, Application $app)
    {
        $this->parameters['extension'] = $app['extensions']->getExtensionById($id);
        if (!$this->parameters['extension']) {
            $app->abort(404);
        }
    }


    public function index($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);

        $this->parameters['userpermissions'] = array();
        foreach ($this->parameters['extension']->getUserPermissions() as $key) {
            $this->parameters['userpermissions'][] = $this->parameters['extension']->getUserPermission($key);
        }

        return $app['twig']->render('sysadmin/extension/index.html.twig', $this->parameters);
    }
}
