<?php
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ParseDomain
{

    /** @var Application */
    protected $app;

    protected $currentDomain;

    public function __construct(Application $application, $currentDomain)
    {
        $this->currentDomain = $currentDomain;
        $this->app = $application;
    }
    
    public function isCoveredByCookies()
    {
        $matchAgainst = $this->stripPort($this->app['config']->webCommonSessionDomain);
        $bit = substr($this->stripPort($this->currentDomain), -strlen($matchAgainst));
        if (strtolower($bit) == strtolower($matchAgainst)) {
            return true;
        }
        
        return false;
    }
    
    protected function stripPort($in)
    {
        $bits = explode(":", $in, 2);
        return $bits[0];
    }
}
