<?php
namespace api1exportbuilders;

use models\SiteModel;
use models\EventModel;
use models\VenueModel;
use models\AreaModel;
use models\CountryModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventListATOMCreateBuilder extends BaseEventListBuilder
{
    use TraitATOM;

    
    
    
    
    public function __construct(Application $app, SiteModel $site = null, string $timeZone  = null)
    {
        parent::__construct($app, $site, $timeZone);
        // We want all events
        // (... is default)
        // order by created at time
        $this->eventRepositoryBuilder->setOrderByCreatedAt(true);
    }
    
    
    public function getContents()
    {
        $txt = '<?xml version="1.0" encoding="utf-8"?>';
        $txt .= '<feed xmlns="http://www.w3.org/2005/Atom">'."\n";
        if ($this->site && !$this->app['config']->isSingleSiteMode) {
            $txt .= '<title>'.
                    ($this->title ? htmlentities($this->title).' - ' : '').
                     htmlentities($this->site->getTitle()).' '.
                     htmlentities($this->app['config']->installTitle).
                    '</title>'."\n";
        } else {
            $txt .= '<title>'.
                    ($this->title ? htmlentities($this->title).' - ' : '').
                     htmlentities($this->app['config']->installTitle).
                    '</title>'."\n";
        }
        $txt .= '<id>'.$this->feedURL.'</id>'."\n";
        $txt .= '<link rel="self" href="'.$this->feedURL.'"/>'."\n";
        $txt .= '<updated>'.date("Y-m-d").'T'.date("H:i:s").'Z</updated>';
        $txt .= implode("", $this->events)."\n";
        $txt .= '</feed>'."\n";
        return $txt;
    }
    
    
    protected function getUpdatedString(EventModel $event)
    {
        $dateIn = $event->getCreatedAt();
        
        return '<updated>'.$dateIn->format("Y-m-d")."T".$dateIn->format("H:i:s")."Z</updated>";
    }
    
    
    public function addEvent(
        EventModel $event,
        $groups = array(),
        VenueModel $venue = null,
        AreaModel $area = null,
        CountryModel $country = null,
        $eventMedias = array()
    ) {
        if ($event->getIsDeleted()) {
            return false;
        }
        
        
        // ########################################### Get Data
        
        $siteSlug = $this->site ? $this->site->getSlug() : $event->getSiteSlug();

        $ourUrl = $this->app['config']->getWebSiteDomainSecure($siteSlug) .'/event/'.$event->getSlugForUrl();

        $dh = new \DateTime('', $this->localTimeZone);
        $dh->setTimestamp($event->getStartAt()->getTimestamp());
        $dateTxt = $dh->format('D j M Y h:ia').' to ';
        $dateTxtShort = $dh->format('D j M');
        $dh->setTimestamp($event->getEndAt()->getTimestamp());
        $dateTxt .= $dh->format('D j M Y h:ia');
        
        
        // ########################################## Build
        $txt = '<entry>';
        $txt .= '<id>'.$ourUrl.'</id>';
        $txt .= '<link href="'.$ourUrl.'"/>';
        
        $txt .= '<title>'.  $this->getData($event->getSummaryDisplay().', '.$dateTxtShort).'</title>';

        $txt .= '<summary>'.$dateTxt.'</summary>';
        
        $content =  '';
        foreach ($this->extraHeaders as $extraHeader) {
            $content .= $extraHeader->getHtml()."<br><br>";
        }
        $content .= $dateTxt."<br>";
        if ($event->getDescription()) {
            $content .=  str_replace("\n", "<br>", htmlentities($event->getDescription(), ENT_QUOTES, 'UTF-8')).'<br>';
        }
        // TODO $event->getUrl()
        $content .= '<a href="'.htmlentities($ourUrl).'">More details at '.htmlentities($ourUrl).'</a><br>';
        $content .= '<p style="font-style:italic;font-size:80%">'.
                    'Powered by <a href="'.$ourUrl.'">'.$this->app['config']->installTitle.'</a>';
        foreach ($this->extraFooters as $extraFooter) {
            $content .= "<br>".$extraFooter->getHtml();
        }
        $content .='</p>';
        
        $txt .= '<content type="html">'.$this->getBigData($content).'</content>';
                
        $txt .= $this->getUpdatedString($event);
        
        $txt .= '<author><name>'.$this->app['config']->installTitle.'</name></author></entry>'." \r\n";
        
        $this->events[] = $txt;
    }
}
