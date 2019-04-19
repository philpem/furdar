<?php

namespace import;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class ImportURLRecommendationDataToCheck
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }


    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
}
