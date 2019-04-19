<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class Request
{
    
    /** @var Symfony\Component\HttpFoundation\Request **/
    protected $symfonyRequest;
    
    public function __construct(Symfony\Component\HttpFoundation\Request $symfonyRequest)
    {
        $this->symfonyRequest = $symfonyRequest;
    }
    
    public function hasGetOrPost($key)
    {
        return $this->symfonyRequest->query->has($key) ||
                $this->symfonyRequest->request->has($key);
    }
    
    public function getGetOrPostString($key, $default)
    {
        if ($this->symfonyRequest->query->has($key)) {
            return $this->symfonyRequest->query->get($key);
        } elseif ($this->symfonyRequest->request->has($key)) {
            return $this->symfonyRequest->request->get($key);
        } else {
            return $default;
        }
    }
    
    public function getGetOrPostBoolean($key, $default)
    {
        if ($this->symfonyRequest->query->has($key)) {
            $value = strtolower(trim($this->symfonyRequest->query->get($key)));
            return substr($value, 0, 2) == 'on'
                || in_array(substr($value, 0, 1), array('1','t','y'));
        } elseif ($this->symfonyRequest->request->has($key)) {
            $value = strtolower(trim($this->symfonyRequest->request->get($key)));
            return substr($value, 0, 2) == 'on'
                || in_array(substr($value, 0, 1), array('1','t','y'));
        } else {
            return $default;
        }
    }
}
