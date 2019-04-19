<?php

namespace tasks;

use repositories\builders\GroupRepositoryBuilder;
use repositories\GroupRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateGroupFutureEventsCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateGroupFutureEventsCache';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateGroupFutureEventsCacheAutomaticUpdateInterval > 0 &&
            $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateGroupFutureEventsCacheAutomaticUpdateInterval;
    }

    protected function run()
    {
        $groupRepository = new GroupRepository($this->app);

        $grb = new GroupRepositoryBuilder($this->app);
        $count = 0;
        foreach ($grb->fetchAll() as $venue) {
            $groupRepository->updateFutureEventsCache($venue);
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
