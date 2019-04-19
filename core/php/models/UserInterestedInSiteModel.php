<?php

namespace models;

use repositories\UserWatchesSiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserInterestedInSiteModel
{
    protected $user_account_id;
    protected $site_id;
    protected $is_interested = false;

    public function setFromDataBaseRow($data)
    {
        $this->user_account_id = $data['user_account_id'];
        $this->site_id = $data['site_id'];
        $this->is_interested = $data['is_interested'];
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

    /**
     * @return boolean
     */
    public function isInterested()
    {
        return $this->is_interested;
    }

    /**
     * @param boolean $is_interested
     */
    public function setIsInterested($is_interested)
    {
        $this->is_interested = $is_interested;
    }
}
