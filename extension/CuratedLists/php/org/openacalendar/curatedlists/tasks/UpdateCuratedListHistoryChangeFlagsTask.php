<?php

namespace org\openacalendar\curatedlists\tasks;

use org\openacalendar\curatedlists\models\CuratedListHistoryModel;
use org\openacalendar\curatedlists\repositories\CuratedListHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class UpdateCuratedListHistoryChangeFlagsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar.curatedlists';
    }

    public function getTaskId()
    {
        return 'UpdateHistoryChangeFlagsTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->getLastRunEndedAgoInSeconds() > 30*60; // TODO $config
    }

    protected function run()
    {
        $curatedListHistoryRepo = new CuratedListHistoryRepository();
        $stat = $this->app['db']->prepare("SELECT * FROM curated_list_history");
        $stat->execute();
        $count = 0;
        while ($data = $stat->fetch()) {
            $curatedListHistory = new CuratedListHistoryModel();
            $curatedListHistory->setFromDataBaseRow($data);

            $curatedListHistoryRepo->ensureChangedFlagsAreSet($curatedListHistory);
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
