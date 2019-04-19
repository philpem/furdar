<?php

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSitePromptEmailModel
{
    protected $user_account_id;
    protected $site_id;
    protected $sent_at;
    
    public function setFromDataBaseRow($data)
    {
        $this->user_account_id = $data['user_account_id'];
        $this->site_id = $data['site_id'];
        $utc = new \DateTimeZone("UTC");
        $this->sent_at = $data['sent_at'] ? new \DateTime($data['sent_at'], $utc) : null;
    }
    
    
    public function getUserAccountId()
    {
        return $this->user_account_id;
    }

    public function setUserAccountId($user_account_id)
    {
        $this->user_account_id = $user_account_id;
    }

    public function getSiteId()
    {
        return $this->site_id;
    }

    public function setSiteId($site_id)
    {
        $this->site_id = $site_id;
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
}
