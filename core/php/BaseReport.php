<?php
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseReport
{


    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    abstract public function getReportTitle();

    abstract public function getExtensionID();

    abstract public function getReportID();

    abstract public function run();


    protected $hasFilterTime = false;

    /**
     * @return boolean
     */
    public function getHasFilterTime()
    {
        return $this->hasFilterTime;
    }

    /**
     * This is INCLUSIVE
     * @var \DateTime
     */
    protected $filterTimeStart = null;

    /**
     * This is INCLUSIVE
     * @var \DateTime
     */
    protected $filterTimeEnd = null;

    public function setFilterTime($filterTimeStart, $filterTimeEnd)
    {
        $this->filterTimeStart = $filterTimeStart;
        $this->filterTimeEnd = $filterTimeEnd;
    }

    protected $hasFilterSite = false;

    /**
     * @return boolean
     */
    public function getHasFilterSite()
    {
        return $this->hasFilterSite;
    }


    protected $filterSiteId;

    /**
     * @param mixed $filterSiteId
     */
    public function setFilterSiteId($filterSiteId)
    {
        $this->filterSiteId = $filterSiteId;
    }
}
