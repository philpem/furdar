<?php
/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


use models\SiteModel;
use Silex\Application;

/**
 *
 * If Request is not SSL, redirect user to SSL.
 *
 * This class does not check config to see if forceSSL is on - it is assumed you've already done that.
 * If forcesSL is not on you should not even instantiate this class as there is no need and it will cause a minor performance hit.
 */
class ForceRequestToSSL
{

    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function processForIndex()
    {
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://".$this->app['config']->webIndexDomainSSL.$_SERVER['REQUEST_URI']);
            die();
        }
    }

    public function processForSite(SiteModel $site)
    {
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
            header("HTTP/1.1 301 Moved Permanently");
            if ($this->app['config']->isSingleSiteMode) {
                header("Location: https://" . $this->app['config']->webSiteDomainSSL . $_SERVER['REQUEST_URI']);
            } else {
                header("Location: https://" . $site->getSlug() . "." . $this->app['config']->webSiteDomainSSL . $_SERVER['REQUEST_URI']);
            }
            die();
        }
    }
}
