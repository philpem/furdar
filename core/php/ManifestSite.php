<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ManifestSite
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get(\models\SiteModel $site)
    {
        return array(
            'name'=>$site->getTitle() . ($this->app['config']->isSingleSiteMode ? '' : ' : '.$this->app['config']->installTitle),
            'short_name'=>$site->getTitle(),
            'start_url'=>'/',
        );
    }
}
