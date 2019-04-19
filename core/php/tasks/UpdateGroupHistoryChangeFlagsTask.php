<?php

namespace tasks;

use models\GroupHistoryModel;
use repositories\GroupHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateGroupHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateGroupHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateGroupHistoryChangeFlagsAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateGroupHistoryChangeFlagsAutomaticUpdateInterval;
    }

    protected function run()
    {
        $groupHistoryRepo = new GroupHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM group_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $groupHistory = new GroupHistoryModel();
            $groupHistory->setFromDataBaseRow($data);

            $groupHistoryRepo->ensureChangedFlagsAreSet($groupHistory);
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
