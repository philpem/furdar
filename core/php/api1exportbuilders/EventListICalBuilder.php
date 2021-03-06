<?php
namespace api1exportbuilders;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use models\EventModel;
use models\SiteModel;
use models\GroupModel;
use models\VenueModel;
use models\AreaModel;
use models\CountryModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventListICalBuilder extends BaseEventListBuilder
{
    use TraitICal;

    /** @var ICalEventIdConfig */
    protected $iCalEventIdConfig;
    
    public function __construct(Application $app, SiteModel $site = null, $timeZone  = null, string $title = null, ICalEventIdConfig $ICalEventIdConfig = null)
    {
        parent::__construct($app, $site, $timeZone, $title);
        // We go back a month, just so calendars have a bit of the past available.
        $time = \TimeSource::getDateTime();
        $time->sub(new \DateInterval("P30D"));
        $this->eventRepositoryBuilder->setAfter($time);
        $this->iCalEventIdConfig = $ICalEventIdConfig ? $ICalEventIdConfig : new ICalEventIdConfig();
    }

    
    public function getContents()
    {
        $txt = $this->getIcalLine('BEGIN', 'VCALENDAR');
        $txt .= $this->getIcalLine('VERSION', '2.0');
        $txt .= $this->getIcalLine('PRODID', '-//OpenACalendar//NONSGML OpenACalendar//EN');
        if ($this->site && !$this->app['config']->isSingleSiteMode) {
            $txt .= $this->getIcalLine('X-WR-CALNAME', ($this->title ? $this->title .' - ' : '').$this->site->getTitle().' '.$this->app['config']->installTitle);
        } else {
            $txt .= $this->getIcalLine('X-WR-CALNAME', ($this->title ? $this->title .' - ' : '').$this->app['config']->installTitle);
        }
        $txt .= implode("", $this->events);
        $txt .= $this->getIcalLine('END', 'VCALENDAR');
        return $txt;
    }
    
    public function getResponse()
    {
        $response = new Response($this->getContents());
        $response->headers->set('Content-Type', 'text/calendar');
        $response->setPublic();
        $response->setMaxAge($this->app['config']->cacheFeedsInSeconds);
        return $response;
    }
    
    public function addEvent(
        EventModel $event,
        $groups = array(),
        VenueModel $venue = null,
        AreaModel $area = null,
        CountryModel $country = null,
        $eventMedias = array()
    ) {
        $siteSlug = $this->site ? $this->site->getSlug() : $event->getSiteSlug();
        
        $txt = $this->getIcalLine('BEGIN', 'VEVENT');
        if ($this->iCalEventIdConfig->isSlug()) {
            $txt .= $this->getIcalLine('UID', $event->getSlug().'@'.$siteSlug.".".$this->app['config']->webSiteDomain);
        } elseif ($this->iCalEventIdConfig->isSlugStartEnd()) {
            $txt .= $this->getIcalLine('UID', $event->getSlug().'-'.
                                             md5($event->getStartAtInUTC()->format('c').'-'.$event->getEndAtInUTC()->format('c')).
                                             '@'.$siteSlug.".".$this->app['config']->webSiteDomain);
        }


        $url = $this->app['config']->getWebSiteDomainSecure($siteSlug) .'/event/'.$event->getSlugForUrl();

        $txt .= $this->getIcalLine('URL', $url);

        if ($event->getIsDeleted()) {
            $txt .= $this->getIcalLine('SUMMARY', $event->getSummaryDisplay(). " [DELETED]");
            $txt .= $this->getIcalLine('METHOD', 'CANCEL');
            $txt .= $this->getIcalLine('STATUS', 'CANCELLED');
            $txt .= $this->getIcalLine('DESCRIPTION', 'DELETED');
        } elseif ($event->getIsCancelled()) {
            $txt .= $this->getIcalLine('SUMMARY', $event->getSummaryDisplay(). " [CANCELLED]");
            $txt .= $this->getIcalLine('METHOD', 'CANCEL');
            $txt .= $this->getIcalLine('STATUS', 'CANCELLED');
            $txt .= $this->getIcalLine('DESCRIPTION', 'CANCELLED');
        } else {
            $txt .= $this->getIcalLine('SUMMARY', $event->getSummaryDisplay());

            $description = '';
            foreach ($this->extraHeaders as $extraHeader) {
                $description .= $extraHeader->getText()."\n\n";
            }
            $description .= $event->getDescription()."\n".
                    //($event->getUrl() ? $event->getUrl()."\n" : '').
                    $url."\n".
                    "Powered by ".$this->app['config']->installTitle;
            foreach ($this->extraFooters as $extraFooter) {
                $description .= "\n".$extraFooter->getText();
            }
            $txt .= $this->getIcalLine('DESCRIPTION', $description);
            
            $descriptionHTML = "<html><body>";
            foreach ($this->extraHeaders as $extraHeader) {
                $descriptionHTML .= "<p>".$extraHeader->getHtml()."</p>";
            }
            $descriptionHTML .=	"<p>".str_replace("\r", "", str_replace("\n", "<br>", htmlentities($event->getDescription())))."</p>";
            //if ($event->getUrl()) $descriptionHTML .= '<p>More info: <a href="'.$event->getUrl().'">'.$event->getUrl().'</a></p>';
            $descriptionHTML .= '<p>More info: <a href="'.$url.'">'.$url.'</a></p>';
            $descriptionHTML .= '<p style="font-style:italic;font-size:80%">Powered by <a href="'.$url.'">'.$this->app['config']->installTitle.'</a>';
            foreach ($this->extraFooters as $extraFooter) {
                $descriptionHTML .= "<br>".$extraFooter->getHtml();
            }
            $descriptionHTML .= '</p>';
            $descriptionHTML .= '</body></html>';
            $txt .= $this->getIcalLine("X-ALT-DESC;FMTTYPE=text/html", $descriptionHTML);
            
            $locationDetails = array();
            if ($event->getVenue() && $event->getVenue()->getTitle()) {
                $locationDetails[] = $event->getVenue()->getTitle();
            }
            if ($event->getVenue() && $event->getVenue()->getAddress()) {
                $locationDetails[] = $event->getVenue()->getAddress();
            }
            if ($event->getArea() && $event->getArea()->getTitle()) {
                $locationDetails[] = $event->getArea()->getTitle();
            }
            if ($event->getVenue() && $event->getVenue()->getAddressCode()) {
                $locationDetails[] = $event->getVenue()->getAddressCode();
            }
            if ($locationDetails) {
                $txt .= $this->getIcalLine('LOCATION', implode(", ", $locationDetails));
            }
            if ($event->getVenue() && $event->getVenue()->getLat() && $event->getVenue()->getLng()) {
                $txt .= $this->getIcalGeoLine($event->getVenue()->getLat(), $event->getVenue()->getLng());
            }
        }
        
        $txt .= $this->getIcalLine('DTSTART', $event->getStartAt()->format("Ymd")."T".$event->getStartAt()->format("His")."Z");
        $txt .= $this->getIcalLine('DTEND', $event->getEndAt()->format("Ymd")."T".$event->getEndAt()->format("His")."Z");

        if ($event->getUpdatedAt()) {
            $txt .= $this->getIcalLine('LAST-MODIFIED', $event->getUpdatedAt()->format("Ymd") . "T" . $event->getUpdatedAt()->format("His") . "Z");
            // 1469647083 is a magic number - it's the timestamp at the time we introduced this feature.
            // Since we can't have any values less than that, we will reduce SEQUENCE by that to keep SEQUENCE reasonably small.
            $txt .= $this->getIcalLine('SEQUENCE', $event->getUpdatedAt()->getTimestamp() - 1469647083);
        } else {
            $txt .= $this->getIcalLine('SEQUENCE', 0);
        }
        if ($event->getCreatedAt()) {
            $txt .= $this->getIcalLine('DTSTAMP', $event->getCreatedAt()->format("Ymd") . "T" . $event->getCreatedAt()->format("His") . "Z");
        } else {
            $txt .= $this->getIcalLine('DTSTAMP', "201001010T010000Z");
        }

        $txt .= $this->getIcalLine('END', 'VEVENT');
        $this->events[] = $txt;
    }
}
