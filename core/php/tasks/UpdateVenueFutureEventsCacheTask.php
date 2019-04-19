<?php

namespace tasks;

use repositories\builders\VenueRepositoryBuilder;
use repositories\VenueRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateVenueFutureEventsCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateVenueFutureEventsCache';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateVenueFutureEventsCacheAutomaticUpdateInterval > 0 &&
            $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateVenueFutureEventsCacheAutomaticUpdateInterval;
    }

    protected function run()
    {
        $venueRepository = new VenueRepository($this->app);

        $vrb = new VenueRepositoryBuilder($this->app);
        $count = 0;
        foreach ($vrb->fetchAll() as $venue) {
            $venueRepository->updateFutureEventsCache($venue);
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
