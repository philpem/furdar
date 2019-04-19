<?php

namespace org\openacalendar\curatedlists\tasks;

use org\openacalendar\curatedlists\models\CuratedListHistoryModel;
use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;
use org\openacalendar\curatedlists\repositories\CuratedListHistoryRepository;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class UpdateCuratedListFutureEventsCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar.curatedlists';
    }

    public function getTaskId()
    {
        return 'UpdateHistoryFutureEventsCacheTask';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->getLastRunEndedAgoInSeconds() > 30*60; // TODO $config
    }


    protected function run()
    {
        $curatedListRepository = new CuratedListRepository();

        $clrb = new CuratedListRepositoryBuilder($this->app);
        $count = 0;
        foreach ($clrb->fetchAll() as $curatedList) {
            $curatedListRepository->updateFutureEventsCache($curatedList);
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
