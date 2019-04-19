<?php

namespace tasks;

/**
 *
 * This task is dead - the thing it did isn't needed now.
 * Left so that past results still show OK in logs for now.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateAreaBoundsCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateAreaBoundsCache';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return false;
    }

    public function getCanRunManuallyNow()
    {
        return false;
    }

    protected function run()
    {
        return array('result'=>'Fail');
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
