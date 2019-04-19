<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class ReportDataItem
{
    protected $labelText;
    protected $labelID;
    protected $labelURL;

    protected $data;

    public function __construct($data, $labelID, $labelText, $labelURL)
    {
        $this->data = $data;
        $this->labelID = $labelID;
        $this->labelText = $labelText;
        $this->labelURL = $labelURL;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getLabelID()
    {
        return $this->labelID;
    }

    /**
     * @return mixed
     */
    public function getLabelText()
    {
        return $this->labelText;
    }

    /**
     * @return mixed
     */
    public function getLabelURL()
    {
        return $this->labelURL;
    }
}
