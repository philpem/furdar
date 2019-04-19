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
class ReportListController
{
    public function index(Request $request, Application $app)
    {
        $reportsSeries = array();
        $reportsValue = array();

        foreach ($app['extensions']->getExtensionsIncludingCore() as $extension) {
            if (method_exists($extension, "getSeriesReports")) {
                $reportsSeries = array_merge($reportsSeries, $extension->getSeriesReports());
            }
            if (method_exists($extension, "getValueReports")) {
                $reportsValue = array_merge($reportsValue, $extension->getValueReports());
            }
        }

        return $app['twig']->render('sysadmin/reportlist/index.html.twig', array(
            'reportsSeries'=>$reportsSeries,
            'reportsValue'=>$reportsValue,
        ));
    }
}
