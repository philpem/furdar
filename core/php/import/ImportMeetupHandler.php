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
class ImportMeetupHandler extends ImportHandlerBase
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
        
        if (in_array(strtolower($urlBits['host']), array('meetup.com','www.meetup.com'))) {
            $bits = explode("/", $urlBits['path']);
            
            if (count($bits) <= 3) {
                // group
                $this->newFeedURL = $this->importRun->getRealUrl();
                if (substr($this->newFeedURL, -1) != '/') {
                    $this->newFeedURL .= '/';
                }
                $this->newFeedURL .= 'events/ical/x/';
                return true;
            } elseif (count($bits) > 3 && $bits[2] == 'events') {
                // specific event
                $this->newFeedURL = $this->importRun->getRealUrl();
                if (substr($this->newFeedURL, -1) != '/') {
                    $this->newFeedURL .= '/';
                }
                $this->newFeedURL .= 'ical/x.ics';
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
