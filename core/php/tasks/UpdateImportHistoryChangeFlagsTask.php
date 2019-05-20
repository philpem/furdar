<?php

namespace tasks;

use models\ImportHistoryModel;
use repositories\ImportHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateImportHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateImportHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateImportURLHistoryChangeFlagsAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateImportURLHistoryChangeFlagsAutomaticUpdateInterval;
    }

    protected function run()
    {
        $importURLHistoryRepo = new ImportHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM import_url_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $importURLHistory = new ImportHistoryModel();
            $importURLHistory->setFromDataBaseRow($data);

            $importURLHistoryRepo->ensureChangedFlagsAreSet($importURLHistory);
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
