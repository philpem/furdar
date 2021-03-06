<?php


namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteHistoryModel extends SiteModel
{
    protected $title_changed = 0;
    protected $slug_changed = 0;
    protected $description_text_changed = 0;
    protected $footer_text_changed = 0;
    protected $is_web_robots_allowed_changed = 0;
    protected $is_closed_by_sys_admin_changed = 0;
    protected $closed_by_sys_admin_reason_changed = 0;
    protected $is_listed_in_index_changed = 0;
    protected $prompt_emails_days_in_advance_changed = 0;

    protected $is_new = 0;


    
    public function setFromDataBaseRow($data)
    {
        $this->id = $data['site_id'];
        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->slug = isset($data['slug']) ? $data['slug'] : null;
        $this->description_text = isset($data['description_text']) ? $data['description_text'] : null;
        $this->footer_text = isset($data['footer_text']) ? $data['footer_text'] : null;
        $this->is_web_robots_allowed = isset($data['is_web_robots_allowed']) ? $data['is_web_robots_allowed'] : null;
        $this->is_closed_by_sys_admin = isset($data['is_closed_by_sys_admin']) ? $data['is_closed_by_sys_admin'] : null;
        $this->closed_by_sys_admin_reason = isset($data['closed_by_sys_admin_reason']) ? $data['closed_by_sys_admin_reason'] : null;
        $this->is_listed_in_index = isset($data['is_listed_in_index']) ? $data['is_listed_in_index'] : null;
        $this->prompt_emails_days_in_advance = isset($data['prompt_emails_days_in_advance']) ? $data['prompt_emails_days_in_advance'] : null;
        $utc = new \DateTimeZone("UTC");
        $this->created_at = new \DateTime($data['created_at'], $utc);
        $this->title_changed  = isset($data['title_changed']) ? $data['title_changed'] : 0;
        $this->slug_changed  = isset($data['slug_changed']) ? $data['slug_changed'] : 0;
        $this->description_text_changed  = isset($data['description_text_changed']) ? $data['description_text_changed'] : 0;
        $this->footer_text_changed  = isset($data['footer_text_changed']) ? $data['footer_text_changed'] : 0;
        $this->is_web_robots_allowed_changed  = isset($data['is_web_robots_allowed_changed']) ? $data['is_web_robots_allowed_changed'] : 0;
        $this->is_closed_by_sys_admin_changed  = isset($data['is_closed_by_sys_admin_changed']) ? $data['is_closed_by_sys_admin_changed'] : 0;
        $this->closed_by_sys_admin_reason_changed  = isset($data['closed_by_sys_admin_reason_changed']) ? $data['closed_by_sys_admin_reason_changed'] : 0;
        $this->is_listed_in_index_changed  = isset($data['is_listed_in_index_changed']) ? $data['is_listed_in_index_changed'] : 0;
        $this->prompt_emails_days_in_advance_changed  = isset($data['prompt_emails_days_in_advance_changed']) ? $data['prompt_emails_days_in_advance_changed'] : 0;
        $this->is_new = isset($data['is_new']) ? $data['is_new'] : 0;
    }
    
    public function isAnyChangeFlagsUnknown()
    {
        return $this->title_changed == 0 ||
            $this->slug_changed == 0 ||
            $this->description_text_changed == 0 ||
            $this->footer_text_changed == 0 ||
            $this->is_web_robots_allowed_changed == 0 ||
            $this->is_closed_by_sys_admin_changed == 0 ||
            $this->closed_by_sys_admin_reason_changed == 0 ||
            $this->is_listed_in_index_changed == 0 ||
            $this->prompt_emails_days_in_advance_changed == 0 ;
    }
    
    public function setChangedFlagsFromNothing()
    {
        $this->title_changed = $this->title ? 1 : -1;
        $this->slug_changed  = $this->slug ? 1 : -1;
        $this->description_text_changed  =  $this->description_text ? 1 : -1;
        $this->footer_text_changed  =  $this->footer_text ? 1 : -1;
        $this->is_web_robots_allowed_changed  = 1;
        $this->is_closed_by_sys_admin_changed  = 1;
        $this->closed_by_sys_admin_reason_changed  =  $this->closed_by_sys_admin_reason ? 1 : -1;
        $this->is_listed_in_index_changed  = 1;
        $this->prompt_emails_days_in_advance_changed  =  1;
        $this->is_new = 1;
    }
    
    public function setChangedFlagsFromLast(SiteHistoryModel $last)
    {
        if ($this->title_changed == 0 && $last->title_changed != -2) {
            $this->title_changed  = ($this->title  != $last->title)? 1 : -1;
        }
        if ($this->slug_changed == 0 && $last->slug_changed != -2) {
            $this->slug_changed   = ($this->slug  != $last->slug)? 1 : -1;
        }
        if ($this->description_text_changed == 0 && $last->description_text_changed != -2) {
            $this->description_text_changed   =  ($this->description_text  != $last->description_text)? 1 : -1;
        }
        if ($this->footer_text_changed == 0 && $last->footer_text_changed != -2) {
            $this->footer_text_changed   =  ($this->footer_text  != $last->footer_text)? 1 : -1;
        }
        if ($this->is_web_robots_allowed_changed == 0 && $last->is_web_robots_allowed_changed != -2) {
            $this->is_web_robots_allowed_changed   = ($this->is_web_robots_allowed  != $last->is_web_robots_allowed)? 1 : -1;
        }
        if ($this->is_closed_by_sys_admin_changed == 0 && $last->is_closed_by_sys_admin_changed != -2) {
            $this->is_closed_by_sys_admin_changed   = ($this->is_closed_by_sys_admin  != $last->is_closed_by_sys_admin)? 1 : -1;
        }
        if ($this->closed_by_sys_admin_reason_changed == 0 && $last->closed_by_sys_admin_reason_changed != -2) {
            $this->closed_by_sys_admin_reason_changed   =  ($this->closed_by_sys_admin_reason  != $last->closed_by_sys_admin_reason)? 1 : -1;
        }
        if ($this->is_listed_in_index_changed == 0 && $last->is_listed_in_index_changed != -2) {
            $this->is_listed_in_index_changed   = ($this->is_listed_in_index  != $last->is_listed_in_index)? 1 : -1;
        }
        if ($this->prompt_emails_days_in_advance_changed == 0 && $last->prompt_emails_days_in_advance_changed != -2) {
            $this->prompt_emails_days_in_advance_changed   =  ($this->prompt_emails_days_in_advance  != $last->prompt_emails_days_in_advance)? 1 : -1;
        }
        $this->is_new = 0;
    }
    
    public function getTitleChanged()
    {
        return ($this->title_changed > -1);
    }

    public function getTitleChangedKnown()
    {
        return ($this->title_changed > -2);
    }

    public function getSlugChanged()
    {
        return ($this->slug_changed > -1);
    }

    public function getSlugChangedKnown()
    {
        return ($this->slug_changed > -2);
    }

    public function getDescriptionTextChanged()
    {
        return ($this->description_text_changed > -1);
    }

    public function getDescriptionTextChangedKnown()
    {
        return ($this->description_text_changed > -2);
    }

    public function getFooterTextChanged()
    {
        return ($this->footer_text_changed > -1);
    }

    public function getFooterTextChangedKnown()
    {
        return ($this->footer_text_changed > -2);
    }

    public function getIsWebRobotsAllowedChanged()
    {
        return ($this->is_web_robots_allowed_changed > -1);
    }

    public function getIsWebRobotsAllowedChangedKnown()
    {
        return ($this->is_web_robots_allowed_changed > -2);
    }

    public function getIsClosedBySysAdminChanged()
    {
        return ($this->is_closed_by_sys_admin_changed > -1);
    }

    public function getIsClosedBySysAdminChangedKnown()
    {
        return ($this->is_closed_by_sys_admin_changed > -2);
    }

    public function getClosedBySyAdminReasonChanged()
    {
        return ($this->closed_by_sys_admin_reason_changed > -1);
    }

    public function getClosedBySyAdminReasonChangedKnown()
    {
        return ($this->closed_by_sys_admin_reason_changed > -2);
    }

    public function getIsListedInIndexChanged()
    {
        return ($this->is_listed_in_index_changed > -1);
    }

    public function getIsListedInIndexChangedKnown()
    {
        return ($this->is_listed_in_index_changed > -2);
    }

    public function getPromptEmailsDaysInAdvanceChanged()
    {
        return ($this->prompt_emails_days_in_advance_changed > -1);
    }

    public function getPromptEmailsDaysInAdvanceChangedKnown()
    {
        return ($this->prompt_emails_days_in_advance_changed > -2);
    }

    public function getIsNew()
    {
        return ($this->is_new == 1);
    }
}
