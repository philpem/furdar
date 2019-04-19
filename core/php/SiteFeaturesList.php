<?php
use models\SiteModel;

/**
 *
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteFeaturesList
{
    protected $featuresAsTree;

    public function __construct($features)
    {
        $this->featuresAsTree = $features;
    }

    public function has($extId, $feature)
    {
        if (array_key_exists($extId, $this->featuresAsTree) && array_key_exists($feature, $this->featuresAsTree[$extId])) {
            return $this->featuresAsTree[$extId][$feature]->isOn();
        }
        return false;
    }

    public function getAsList()
    {
        $out = array();
        foreach ($this->featuresAsTree as $features) {
            foreach ($features as $feature) {
                $out[] = $feature;
            }
        }
        return $out;
    }
}
