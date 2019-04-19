<?php

namespace tasks;

use models\VenueHistoryModel;
use repositories\VenueHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateVenueHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateVenueHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateHistoryChangeFlagsTaskAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateHistoryChangeFlagsTaskAutomaticUpdateInterval;
    }

    protected function run()
    {
        $venueHistoryRepo = new VenueHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM venue_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $venueHistory = new VenueHistoryModel();
            $venueHistory->setFromDataBaseRow($data);

            $venueHistoryRepo->ensureChangedFlagsAreSet($venueHistory);
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
