<?php


namespace usernotifications\models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpcomingEventsUserNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'UpcomingEvents';
    }

    public function setUpcomingEvents($events)
    {
        $this->data['upcomingevents'] = array();
        foreach ($events as $event) {
            $this->data['upcomingevents'][] = $event->getId();
        }
    }

    public function setAllEvents($events)
    {
        $this->data['allevents'] = array();
        foreach ($events as $event) {
            $this->data['allevents'][] = $event->getId();
        }
    }
    
    public function getNotificationText()
    {
        return "You have upcoming events";
    }
    
    public function getNotificationURL()
    {
        global $CONFIG;
        return $CONFIG->getWebIndexDomainSecure().'/me/agenda';
    }
}
