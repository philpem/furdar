<?php

namespace siteapi1\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use api1exportbuilders\HistoryListATOMBuilder;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class HistoryListController
{
    public function atom(Request $request, Application $app)
    {
        $atom = new HistoryListATOMBuilder($app, $app['currentSite'], $app['currentTimeZone']);
        $atom->build();
        return $atom->getResponse();
    }
}
