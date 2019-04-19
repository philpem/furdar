<?php

namespace tasks;

use repositories\builders\AreaRepositoryBuilder;
use repositories\AreaRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateAreaFutureEventsCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateAreaFutureEventsCache';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateAreaFutureEventsCacheAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateAreaFutureEventsCacheAutomaticUpdateInterval;
    }

    protected function run()
    {
        $areaRepository = new AreaRepository($this->app);

        $arb = new AreaRepositoryBuilder($this->app);
        $arb->setLimit(0);  // all of them
        $count = 0;
        foreach ($arb->fetchAll() as $area) {
            $areaRepository->updateFutureEventsCache($area);
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
