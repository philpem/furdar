<?php

namespace import;

use import\ImportHandlerBase;
use TimeSource;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportLanyrdHandler extends ImportHandlerBase
{
    public function getSortOrder()
    {
        return 100000;
    }
    
    public function isStopAfterHandling()
    {
        return false;
    }
    
    protected $newFeedURL;
    
    public function canHandle()
    {
        $urlBits = parse_url($this->importRun->getRealURL());
        
        if (in_array(strtolower($urlBits['host']), array('lanyrd.com','www.lanyrd.com'))) {
            $bits = explode("/", $urlBits['path']);
            $allowedYears = array(date('Y', TimeSource::time()),date('Y', TimeSource::time())+1);
            if (count($bits) > 3 && in_array($bits[1], $allowedYears) && $bits[2]) {
                $this->newFeedURL = $this->importRun->getRealURL();
                if (substr($this->newFeedURL, -1) != '/') {
                    $this->newFeedURL .= '/';
                }
                $this->newFeedURL .= $bits[2].".ics";
                return true;
            }
        }
        
        return false;
    }
    
    public function getNewFeedURL()
    {
        return $this->newFeedURL;
    }
    
    public function handle()
    {
        if ($this->newFeedURL) {
            $this->importRun->setRealUrl($this->newFeedURL);
        }
    }
}
