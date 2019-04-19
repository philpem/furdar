<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseSiteFeature
{
    protected $is_on = false;

    abstract public function getExtensionId();
    abstract public function getFeatureId();

    /**
     * @return boolean
     */
    public function isOn()
    {
        return $this->is_on;
    }

    /**
     * @param boolean $is_on
     */
    public function setOn($is_on)
    {
        $this->is_on = $is_on;
    }


    public function getTitle()
    {
        return $this->getFeatureId();
    }

    public function getDescription()
    {
        return '';
    }
}
