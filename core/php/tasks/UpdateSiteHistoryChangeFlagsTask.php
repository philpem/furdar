<?php

namespace tasks;

use models\SiteHistoryModel;
use repositories\SiteHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateSiteHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateSiteHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateSiteHistoryChangeFlagsAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateSiteHistoryChangeFlagsAutomaticUpdateInterval;
    }

    protected function run()
    {
        $siteHistoryRepo = new SiteHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM site_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $siteHistory = new SiteHistoryModel();
            $siteHistory->setFromDataBaseRow($data);

            $siteHistoryRepo->ensureChangedFlagsAreSet($siteHistory);
            ++$count;
        }

        return array('result'=>'ok','count'=>$count);
    }

    public function getResultDataAsString(\models\TaskLogModel $taskLogModel)
    {
        if ($taskLogModel->getIsResultDataHaveKey("result") && $taskLogModel->getResultDataValue("result") == "ok") {
            return "Ok";
        } else {
            return "Fail";
        }
    }
}
