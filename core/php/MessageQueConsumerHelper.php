<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MessageQueConsumerHelper
{
    protected $pheanstalk;

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }


    protected function loadMessageQue()
    {
        if ($this->pheanstalk && $this->pheanstalk->getConnection()->isServiceListening()) {
            return true;
        }

        if ($this->app['config']->useBeanstalkd) {
            $this->pheanstalk = new \Pheanstalk\Pheanstalk(
                $this->app['config']->beanstalkdHost,
                $this->app['config']->beanstalkdPort,
                $this->app['config']->beanstalkdProducerConnectTimeOut
            );

            if ($this->pheanstalk->getConnection()->isServiceListening()) {
                return true;
            }
        }

        return false;
    }

    /**
     */
    public function runWorkers()
    {
        if ($this->loadMessageQue()) {
            $this->app['monolog']->info('Starting New Message Que Consumer Workers Run');

            $workers = array();

            foreach ($this->app['extensions']->getExtensionsIncludingCore() as $extension) {
                $workers = array_merge($workers, $extension->getMessageQueWorkers());
            }


            $started = time();

            while (time() < $started + $this->app['config']->messageQueConsumerProcessRunsForSeconds) {
                $job = $this->pheanstalk
                    ->watch($this->app['config']->beanstalkdTube)
                    ->ignore('default')
                    ->reserve($this->app['config']->messageQueConsumerProcessChecksEverySeconds);

                if ($job) {
                    $jobData = json_decode($job->getData(), true);


                    $this->app['monolog']->info('Got Message Que Job', array('extension'=>$jobData['extension'], 'type'=>$jobData['type']));

                    foreach ($workers as $worker) {
                        $worker->process($jobData['extension'], $jobData['type'], $jobData['data']);
                    }

                    $this->pheanstalk->delete($job);
                }
            }

            $this->app['monolog']->info('Finished Message Que Consumer Workers Run');
        }
    }
}
