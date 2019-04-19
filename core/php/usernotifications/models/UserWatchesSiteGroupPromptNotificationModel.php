<?php


namespace usernotifications\models;

use models\GroupModel;
use repositories\GroupRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSiteGroupPromptNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'UserWatchesSiteGroupPrompt';
    }
    
    public function setGroup(GroupModel $group)
    {
        $this->data['group'] = $group->getId();
    }

    /** @var GroupModel  **/
    public $group;
    
    private function loadGroupIfNeeded()
    {
        global $app;
        if (!$this->group && property_exists($this->data, 'group') && $this->data->group) {
            $repo = new GroupRepository($app);
            $this->group = $repo->loadById($this->data->group);
        }
    }
    
    public function getNotificationText()
    {
        $this->loadGroupIfNeeded();
        return "There will soon be no more events in the group: ".$this->group->getTitle();
    }
    
    public function getNotificationURL()
    {
        global $CONFIG;
        $this->loadGroupIfNeeded();
        return $CONFIG->getWebSiteDomainSecure($this->site->getSlug()).'/group/'.$this->group->getSlugForUrl();
    }
}
