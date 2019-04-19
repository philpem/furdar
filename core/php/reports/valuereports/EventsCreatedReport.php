<?php

namespace reports\valuereports;

use BaseValueReport;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventsCreatedReport extends BaseValueReport
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->hasFilterTime = true;
        $this->hasFilterSite = true;
    }

    public function getExtensionID()
    {
        return 'org.openacalendar';
    }

    public function getReportTitle()
    {
        return "Events Created";
    }

    public function getReportID()
    {
        return "EventsCreated";
    }

    public function run()
    {
        $where = array();
        $params = array();

        if ($this->filterTimeStart) {
            $where[] = " event_information.created_at >= :start_at ";
            $params['start_at'] = $this->filterTimeStart->format("Y-m-d H:i:s");
        }


        if ($this->filterTimeEnd) {
            $where[] = " event_information.created_at <= :end_at ";
            $params['end_at'] = $this->filterTimeEnd->format("Y-m-d H:i:s");
        }

        if ($this->filterSiteId) {
            $where[] = " event_information.site_id = :site_id ";
            $params['site_id'] = $this->filterSiteId;
        }

        $sql = "SELECT COUNT(*) AS count  ".
            " FROM event_information ".
            ($where ? " WHERE " . implode(" AND ", $where) : "");

        $stat = $this->app['db']->prepare($sql);
        $stat->execute($params);
        $data = $stat->fetch();
        $this->data = $data['count'];
    }
}
