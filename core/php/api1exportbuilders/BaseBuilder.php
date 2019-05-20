<?php
namespace api1exportbuilders;

use repositories\builders\EventRepositoryBuilder;
use models\SiteModel;
use models\EventModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseBuilder
{

    /** @var Application */
    protected $app;

    protected $title;
    
    /** @var SiteModel **/
    protected $site;


    
    protected $localTimeZone;
    
    public function __construct(Application $app, SiteModel $site = null, $timeZone = null, $title = null)
    {
        $this->app = $app;
        $this->site = $site;
        $this->localTimeZone = new \DateTimeZone($timeZone ? $timeZone : "UTC");
        $this->title = $title;
        if ($this->app['config']->apiExtraHeader1Html || $this->app['config']->apiExtraHeader1Text) {
            $this->addExtraHeader($this->app['config']->apiExtraHeader1Html, $this->app['config']->apiExtraHeader1Text);
        }
        if ($this->app['config']->apiExtraFooter1Html || $this->app['config']->apiExtraFooter1Text) {
            $this->addExtraFooter($this->app['config']->apiExtraFooter1Html, $this->app['config']->apiExtraFooter1Text);
        }
    }

    
    abstract public function getContents();
    abstract public function getResponse();
    
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    protected $extraHeaders = array();
    
    public function addExtraHeader($html, $text)
    {
        $this->extraHeaders[] = new ExportBuilderExtraHeaderOrFooter($html, $text);
    }

    protected $extraFooters = array();

    public function addExtraFooter($html, $text)
    {
        $this->extraFooters[] = new ExportBuilderExtraHeaderOrFooter($html, $text);
    }
}

class ExportBuilderExtraHeaderOrFooter
{
    protected $html;
    protected $text;
    public function __construct($html, $text)
    {
        $this->html = $html;
        $this->text = $text;
    }
    public function getHtml()
    {
        return $this->html;
    }
    public function getText()
    {
        return $this->text;
    }
}
