<?php

namespace tasks;

use models\MediaHistoryModel;
use repositories\MediaHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateMediaHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateMediaHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateMediaHistoryChangeFlagsAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateMediaHistoryChangeFlagsAutomaticUpdateInterval;
    }

    protected function run()
    {
        $mediaHistoryRepo = new MediaHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM media_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $mediaHistory = new MediaHistoryModel();
            $mediaHistory->setFromDataBaseRow($data);

            $mediaHistoryRepo->ensureChangedFlagsAreSet($mediaHistory);
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
