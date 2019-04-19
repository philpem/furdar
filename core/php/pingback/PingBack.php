<?php


namespace pingback;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class PingBack
{
    protected $source_url;

    protected $target_url;

    public function __construct(string $source_url, string $target_url)
    {
        $this->source_url = $source_url;
        $this->target_url = $target_url;
    }

    /**
     * @return mixed
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }

    /**
     * @return mixed
     */
    public function getTargetUrl()
    {
        return $this->target_url;
    }
}
