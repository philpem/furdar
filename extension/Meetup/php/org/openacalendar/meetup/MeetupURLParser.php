<?php

namespace org\openacalendar\meetup;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MeetupURLParser
{
    protected $eventId;
    protected $groupName;


    public function __construct($url)
    {
        $urlBits = parse_url($url);

        if (isset($urlBits['host']) && in_array(strtolower($urlBits['host']), array('meetup.com','www.meetup.com'))) {
            $bits = explode("/", $urlBits['path']);

            if (count($bits) <= 3) {
                $this->groupName = $bits[1];
                return true;
            } elseif (count($bits) > 3 && $bits[2] == 'events') {
                $this->groupName = $bits[1];
                $this->eventId = $bits[3];
                return true;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->groupName;
    }
}
