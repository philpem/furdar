<?php


namespace sysadmin\controllers;

use reports\SeriesOfValueByTimeReport;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use sysadmin\forms\RunValueByTimeReportForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class ValueByTimeReportController
{
    protected $report;

    public function build($extid, $reportId, Application $app)
    {
        $extension = $app['extensions']->getExtensionById($extid);
        if (!$extension) {
            return false;
        }
        foreach ($extension->getValueReports() as $report) {
            if ($report->getReportID() == $reportId && $report->getHasFilterTime()) {
                $this->report = $report;
                return true;
            }
        }
        return false;
    }

    public function index($extid, $reportid, Request $request, Application $app)
    {
        if (!$this->build($extid, $reportid, $app)) {
            die("NO");
        }



        $form = $app['form.factory']->create(RunValueByTimeReportForm::class, null, ['app'=>$app,'report'=>$this->report]);

        return $app['twig']->render('sysadmin/valuebytimereport/index.html.twig', array(
            'report'=>$this->report,
            'form'=>$form->createView(),
        ));
    }

    public function run($extid, $reportid, Request $request, Application $app)
    {
        if (!$this->build($extid, $reportid, $app)) {
            die("NO");
        }

        $form = $app['form.factory']->create(RunValueByTimeReportForm::class, null, ['app'=>$app,'report'=>$this->report]);
        $form->handleRequest($request);

        $startAt = $form->get('start_at')->getData();
        $endAt = $form->get('end_at')->getData();
        $period = $form->get('timeperiod')->getData();
        $filterSiteID = $this->report->getHasFilterSite() ? $form->get('site_id')->getData() : null;

        $this->report->setFilterSiteId($filterSiteID);

        $reportByTime = new SeriesOfValueByTimeReport($this->report, $startAt, $endAt, $period);
        $reportByTime->run();

        if ($form->get('output')->getData() == 'htmlTable') {
            return $app['twig']->render('sysadmin/valuebytimereport/run.table.html.twig', array(
                'report'=>$this->report,
                'reportData'=>$reportByTime->getData(),
                'startAt'=>$startAt,
                'endAt'=>$endAt,
                'filterSiteId'=>$filterSiteID,
            ));
        } elseif ($form->get('output')->getData() == 'csv') {
            $csv = "Label Text, Count\n";
            foreach ($reportByTime->getData() as $data) {
                $csv .= '"'.str_replace('"', '', $data->getLabelText()).'",'.$data->getData()."\n";
            }

            $response = new Response($csv);
            $response->headers->set('Content-Type', 'text/csv');
            return $response;
        }
    }
}
