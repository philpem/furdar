<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class FlashMessages
{
    
    /** @var WebSession **/
    protected $WebSession;
    
    public function __construct(WebSession $WebSession)
    {
        $this->WebSession = $WebSession;
    }

    public function addMessage($string)
    {
        $this->WebSession->appendArray("flashMessage", $string);
    }
    
    public function addError($string)
    {
        $this->WebSession->appendArray("flashError", $string);
    }
    
    public function getAndClearMessages()
    {
        $out = $this->WebSession->getArray("flashMessage");
        if ($out) {
            $this->WebSession->setArray("flashMessage", array());
        }
        return $out;
    }
    
    public function getAndClearErrors()
    {
        $out = $this->WebSession->getArray("flashError");
        if ($out) {
            $this->WebSession->setArray("flashError", array());
        }
        return $out;
    }
}
