<?php

namespace tasks;

use models\TagHistoryModel;
use repositories\TagHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateTagHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateTagHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateTagHistoryChangeFlagsAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateTagHistoryChangeFlagsAutomaticUpdateInterval;
    }

    protected function run()
    {
        $tagHistoryRepo = new TagHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM tag_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $tagHistory = new TagHistoryModel();
            $tagHistory->setFromDataBaseRow($data);

            $tagHistoryRepo->ensureChangedFlagsAreSet($tagHistory);
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
