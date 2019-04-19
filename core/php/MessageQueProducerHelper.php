<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MessageQueProducerHelper
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

    public function send($extension, $type, $data)
    {
        if ($this->loadMessageQue()) {
            $this->pheanstalk->useTube($this->app['config']->beanstalkdTube)->put(json_encode(array('extension'=>$extension, 'type'=>$type, 'data'=>$data)));
        }
    }

    public function hasMessageQue()
    {
        return $this->loadMessageQue();
    }
}
