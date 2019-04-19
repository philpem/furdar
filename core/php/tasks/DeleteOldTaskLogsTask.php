<?php

namespace tasks;

use Silex\Application;

/**
 *
 * Deletes Old Task Logs. Meta.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class DeleteOldTaskLogsTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'DeleteOldTaskLogs';
    }


    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskDeleteOldTaskLogsAutomaticRunInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskDeleteOldTaskLogsAutomaticRunInterval;
    }

    protected function run()
    {
        $before = $this->app['timesource']->getDateTime();
        $before->setTimestamp($before->getTimestamp() - $this->app['config']->taskDeleteOldTaskLogsDeleteOlderThan);

        $stat = $this->app['db']->prepare("DELETE FROM task_log ".
            "WHERE started_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        return array('result' => 'ok');
    }
}
