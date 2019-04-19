<?php

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesGroupPromptEmailModel
{
    protected $user_account_id;
    protected $group_id;
    protected $sent_at;
    
    /** secondary field **/
    protected $site_id;
    /** secondary field **/
    protected $group_slug;
    /** secondary field **/
    protected $group_title;

    public function setFromDataBaseRow($data)
    {
        $this->user_account_id = $data['user_account_id'];
        $this->group_id = $data['group_id'];
        $utc = new \DateTimeZone("UTC");
        $this->sent_at = $data['sent_at'] ? new \DateTime($data['sent_at'], $utc) : null;
        $this->site_id = $data['site_id'];
        $this->group_slug = $data['group_slug'];
        $this->group_title = $data['group_title'];
    }
    
    
    public function getUserAccountId()
    {
        return $this->user_account_id;
    }

    public function setUserAccountId($user_account_id)
    {
        $this->user_account_id = $user_account_id;
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    public function getSentAt()
    {
        return $this->sent_at;
    }

    public function setSentAt($sent_at)
    {
        $this->sent_at = $sent_at;
        return $this;
    }


    
    public function getSiteId()
    {
        return $this->site_id;
    }


    public function getGroupSlug()
    {
        return $this->group_slug;
    }

    public function getGroupTitle()
    {
        return $this->group_title;
    }
}
