<?php

namespace org\openacalendar\meetup;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLRecommendation implements \InterfaceImportURLRecommendation
{
    protected $newURL;

    public function __construct($url)
    {
        $meetupURLParser = new MeetupURLParser($url);
        if ($meetupURLParser->getGroupName() && $meetupURLParser->getEventId()) {
            $this->newURL = 'http://www.meetup.com/'.$meetupURLParser->getGroupName().'/';
        }
    }

    public function hasNewURL()
    {
        return (boolean)$this->newURL;
    }

    public function getNewURL()
    {
        return $this->newURL;
    }

    public function getTitle()
    {
        return "Do you want to import all events in this group instead?";
    }

    public function getDescription()
    {
        return "This will import one event only. Instead, you can import all current and future events in this group.";
    }

    public function getActionAcceptLabel()
    {
        return "Yes, import all events in this group.";
    }

    public function getActionRefuseLabel()
    {
        return "No, just import one event.";
    }

    public function getExtensionID()
    {
        return "org.openacalendar.meetup";
    }

    public function getRecommendationID()
    {
        return "ImportGroupInstead";
    }
}
