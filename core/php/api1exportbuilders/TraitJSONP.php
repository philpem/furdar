<?php

namespace api1exportbuilders;

use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @package Core
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


trait TraitJSONP
{
    protected $callBackFunction = 'callback';
        
    public function setCallBackFunction($cbf)
    {
        $this->callBackFunction = $cbf;
    }
    
    public function getContents()
    {
        return $this->callBackFunction."(".parent::getContents().");";
    }

    public function getResponse()
    {
        $response = new Response($this->getContents());
        $response->headers->set('Content-Type', 'text/javascript');
        $response->setPublic();
        $response->setMaxAge($this->app['config']->cacheFeedsInSeconds);
        return $response;
    }
}
