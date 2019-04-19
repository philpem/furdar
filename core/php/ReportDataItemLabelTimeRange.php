<?php



/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class ReportDataItemLabelTimeRange
{

    /** @var  \DateTime */
    protected $labelStart;

    /** @var  \DateTime */
    protected $labelEnd;

    protected $data;

    public function __construct($labelStart, $labelEnd, $data=null)
    {
        $this->data = $data;
        $this->labelEnd = clone $labelEnd;
        $this->labelStart = clone $labelStart;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \DateTime
     */
    public function getLabelEnd()
    {
        return $this->labelEnd;
    }

    /**
     * @return \DateTime
     */
    public function getLabelStart()
    {
        return $this->labelStart;
    }

    public function getLabelText()
    {
        return $this->labelStart->format("Y-m-d H:i:s") . " - " . $this->labelEnd->format("Y-m-d H:i:s");
    }

    /**
     * @param null $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
