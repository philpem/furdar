<?php


namespace usernotifications\models;

use models\GroupModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesNotifyNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'UserWatchesNotify';
        $this->data = array('content'=>array());
    }
    
    public function getNotificationText()
    {
        if (count($this->data->content) == 1) {
            return "There are changes to ".$this->data->content[0]->watchedThingTitle;
        } else {
            $out = array();
            foreach ($this->data->content as $content) {
                $out[] = $content->watchedThingTitle;
            }
            return "There are changes to: ".implode(", ", $out);
        }
    }
    
    public function getNotificationURL()
    {
        // TODO - really bad to store URL in notification, what if site home changes!
        // TODO - what if more than 1 URL !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        return $this->data->content[0]->watchedThingURL;
    }

    public function addContent(\BaseUserWatchesNotifyContent $content)
    {
        $this->data['content'][] = array(
            'watchedThingTitle'=>$content->getWatchedThingTitle(),
            'watchedThingURL'=>$content->getWatchedThingURL(),
        );
    }
}
