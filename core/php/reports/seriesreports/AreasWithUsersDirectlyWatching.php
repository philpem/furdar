<?php


namespace reports\seriesreports;

use BaseSeriesReport;
use ReportDataItem;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class AreasWithUsersDirectlyWatching extends BaseSeriesReport
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->hasFilterTime = false;
        $this->hasFilterSite = true;
    }

    public function getExtensionID()
    {
        return 'org.openacalendar';
    }

    public function getReportID()
    {
        return 'AreasWithUsersDirectlyWatching';
    }

    public function getReportTitle()
    {
        return 'Areas With Users Directly Watching';
    }

    public function run()
    {
        $where = array();
        $params = array();

        if ($this->filterSiteId) {
            $where[] = " area_information.site_id = :site_id ";
            $params['site_id'] = $this->filterSiteId;
        }

        $sql = "SELECT area_information.id, area_information.title,  area_information.slug, area_information.site_id, count(user_watches_area_information.user_account_id) AS count FROM user_watches_area_information ".
            " JOIN area_information ON area_information.id = user_watches_area_information.area_id ".
            "WHERE user_watches_area_information.is_watching = '1' ".
            ($where ? " AND " . implode(" AND ", $where) : "").
            "GROUP BY area_information.id ".
            "ORDER BY count DESC ";
        $stat = $this->app['db']->prepare($sql);
        $stat->execute($params);
        $this->data = array();
        while ($data = $stat->fetch()) {
            $this->data[$data['id']] = new ReportDataItem($data['count'], $data['id'], $data['title'], '/sysadmin/site/'.$data['site_id'].'/area/'.$data['slug']);
        }
    }
}
