<?php

namespace tasks;

use Silex\Application;

/**
 *
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class DeleteOldHistoryIps extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'DeleteOldHistoryIps';
    }


    public function getShouldRunAutomaticallyNow()
    {
        return  $this->app['config']->taskDeleteOldHistoryIpsDeleteOlderThan > 0 &&
            $this->app['config']->taskDeleteOldHistoryIpsRunInterval > 0 &&
            $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskDeleteOldHistoryIpsRunInterval;
    }

    protected function run()
    {
        $before = $this->app['timesource']->getDateTime();
        $before->setTimestamp($before->getTimestamp() - $this->app['config']->taskDeleteOldHistoryIpsDeleteOlderThan);

        $stat = $this->app['db']->prepare("UPDATE event_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        $stat = $this->app['db']->prepare("UPDATE group_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        $stat = $this->app['db']->prepare("UPDATE area_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        $stat = $this->app['db']->prepare("UPDATE venue_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        $stat = $this->app['db']->prepare("UPDATE tag_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        $stat = $this->app['db']->prepare("UPDATE media_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));

        $stat = $this->app['db']->prepare("UPDATE site_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));


        $stat = $this->app['db']->prepare("UPDATE user_group_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));


        $stat = $this->app['db']->prepare("UPDATE import_url_history SET from_ip=null ".
            "WHERE created_at < :before");
        $stat->execute(array(
            'before'=>$before->format("Y-m-d H:i:s"),
        ));



        return array('result' => 'ok');
    }
}
