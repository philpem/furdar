<?php

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserNotificationPreferenceModel
{
    protected $is_email;
    
    public function setFromDataBaseRow($data)
    {
        $this->is_email = (boolean)$data['is_email'];
    }
    
    public function getIsEmail()
    {
        return $this->is_email;
    }

    public function setIsEmail($is_email)
    {
        $this->is_email = $is_email;
    }
}
