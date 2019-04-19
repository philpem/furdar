<?php

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventEditMetaDataModel extends \BaseEditMetaDataModel
{
    protected $createdFromNewEventDraftID;

    /**
     * @return mixed
     */
    public function getCreatedFromNewEventDraftID()
    {
        return $this->createdFromNewEventDraftID;
    }

    /**
     * @param mixed $createdFromNewEventDraftID
     */
    public function setCreatedFromNewEventDraftID($createdFromNewEventDraftID)
    {
        $this->createdFromNewEventDraftID = $createdFromNewEventDraftID;
    }
}
