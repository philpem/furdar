<?php

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserInSiteModel
{
    protected $user_account_id;
    protected $site_id;
    protected $is_owner = false;
    protected $is_administrator= false;
    protected $is_editor = false;
    
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

    public function getIsOwner()
    {
        return $this->is_owner;
    }

    public function setIsOwner($is_owner)
    {
        $this->is_owner = $is_owner;
    }
    
    public function getIsAdministrator()
    {
        return $this->is_administrator;
    }

    public function setIsAdministrator($is_administrator)
    {
        $this->is_administrator = $is_administrator;
    }

    public function getIsEditor()
    {
        return $this->is_editor;
    }

    public function setIsEditor($is_editor)
    {
        $this->is_editor = $is_editor;
    }

            
    public function setFromDataBaseRow($data)
    {
        $this->user_account_id = $data['user_account_id'];
        $this->site_id = $data['site_id'];
        $this->is_owner = (boolean)$data['is_owner'];
        $this->is_administrator = (boolean)$data['is_administrator'];
        $this->is_editor = (boolean)$data['is_editor'];
    }
}
