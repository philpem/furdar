<?php

namespace sysadmin\controllers;

use BaseReport;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use sysadmin\forms\RunSeriesReportForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SeriesReportController
{

    /** @var  BaseReport */
    protected $report;

    public function build($extid, $reportId, Application $app)
    {
        $extension = $app['extensions']->getExtensionById($extid);
        if (!$extension) {
            return false;
        }
        foreach ($extension->getSeriesReports() as $report) {
            if ($report->getReportID() == $reportId) {
                $this->report = $report;
                return true;
            }
        }
        return false;
    }

    public function index($extid, $reportid, Request $request, Application $app)
    {
        if (!$this->build($extid, $reportid, $app)) {
            die("NO REPORT FOUND"); // TODO
        }



        $form = $app['form.factory']->create(RunSeriesReportForm::class, null, ['app'=>$app,'report'=>$this->report]);

        return $app['twig']->render('sysadmin/seriesreport/index.html.twig', array(
            'report'=>$this->report,
            'form'=>$form->createView(),
        ));
    }

    public function run($extid, $reportid, Request $request, Application $app)
    {
        if (!$this->build($extid, $reportid, $app)) {
            die("NO");
        }

        $form = $app['form.factory']->create(RunSeriesReportForm::class, null, ['app'=>$app,'report'=>$this->report]);
        $form->handleRequest($request);

        $filterStartAt = $filterEndAt = $filterSiteID = null;
        if ($this->report->getHasFilterTime()) {
            $filterStartAt = $form->get('start_at')->getData();
            $filterEndAt = $form->get('end_at')->getData();
            $this->report->setFilterTime($filterStartAt, $filterEndAt);
        }
        if ($this->report->getHasFilterSite()) {
            $filterSiteID = $form->get('site_id')->getData();
            $this->report->setFilterSiteId($filterSiteID);
        }

        $this->report->run();

        if ($form->get('output')->getData() == 'htmlTable') {
            return $app['twig']->render('sysadmin/seriesreport/run.table.html.twig', array(
                'report'=>$this->report,
                'filterStartAt'=>$filterStartAt,
                'filterEndAt'=>$filterEndAt,
                'filterSiteId'=>$filterSiteID,
            ));
        } elseif ($form->get('output')->getData() == 'csv') {
            $csv = "Label ID, Label Text, Count\n";
            foreach ($this->report->getData() as $data) {
                $csv .= $data->getLabelID().',"'.str_replace('"', '', $data->getLabelText()).'",'.$data->getData()."\n";
            }

            $response = new Response($csv);
            $response->headers->set('Content-Type', 'text/csv');
            return $response;
        }
    }
}
