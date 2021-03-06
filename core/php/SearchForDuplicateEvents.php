<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

use models\EventModel;
use models\SiteModel;
use repositories\builders\EventRepositoryBuilder;

class SearchForDuplicateEvents
{

    /** @var EventModel **/
    protected $event;
    
    /** @var Site **/
    protected $site;
            
    protected $showEventsCount = 3;
    
    protected $showEventsThreshhold = 2;

    protected $wordsNotToMatchInSummary = array();
            
    public function __construct(EventModel $event, SiteModel $site, $showEventsCount=3, $showEventsThreshhold=2, $wordsNotToMatchInSummary=array())
    {
        $this->event = $event;
        $this->site = $site;
        $this->showEventsCount = $showEventsCount;
        $this->showEventsThreshhold = $showEventsThreshhold;
        $this->wordsNotToMatchInSummary = $wordsNotToMatchInSummary;
    }
    
    protected $notTheseSlugs = array();
    
    public function setNotDuplicateSlugs($in)
    {
        foreach ($in as $slug) {
            if ($slug) {
                $this->notTheseSlugs[] = $slug;
            }
        }
    }
    
    public function getPossibleDuplicates()
    {
        global $app;

        /**
         * If no Start or End time on event then we aren't even going to try to look for dupes.
         * There would be to many options and not enought to search on.
         */
        if (!$this->event->getStartAt() || !$this->event->getEndAt()) {
            return array();
        }
        
        ## Get events
        $eventRepositoryBuilder = new EventRepositoryBuilder($app);
        $eventRepositoryBuilder->setSite($this->site);
        $eventRepositoryBuilder->setIncludeAreaInformation(true);

        $eventRepositoryBuilder->setIncludeDeleted(true);
        $eventRepositoryBuilder->setIncludeCancelled(true);
        
        $after = clone $this->event->getStartAt();
        $after->sub(new \DateInterval("PT4H"));
        $eventRepositoryBuilder->setAfter($after);
        
        $before = clone $this->event->getStartAt();
        $before->add(new \DateInterval("PT4H"));
        $eventRepositoryBuilder->setBefore($before);

        $events  = $eventRepositoryBuilder->fetchAll();
        
        ## Score
        $eventsWithScore = array();
        foreach ($events as $event) {
            if (!in_array($event->getSlug(), $this->notTheseSlugs)) {
                $eventsWithScore[] = array(
                    'event'=>$event,
                    'score'=>$this->getScoreForConsideredEvent($event),
                );
            }
        }
        
        ## sort
        $sortFunc = function ($a, $b) {
            if ($a['score'] == $b['score']) {
                return 0;
            } elseif ($a['score'] > $b['score']) {
                return 1;
            } elseif ($a['score'] < $b['score']) {
                return -1;
            };
        };
        usort($eventsWithScore, $sortFunc);
        
        ## Results
        $results = array();
        foreach ($eventsWithScore as $eventWithScore) {
            if (count($results) < $this->showEventsCount && $eventWithScore['score'] >= $this->showEventsThreshhold) {
                $results[] = $eventWithScore['event'];
            }
        }
        
        return $results;
    }
    
    
    public function getScoreForConsideredEvent(EventModel $event)
    {
        $score = 0;
        
        // Only 1 of Start or End matching can count as a point. To many false positives when both matched.
        // Due to standard event time of 2 hours, an event that happened to be at same start and end time would show as match when it was completely different.
        if ($this->event->getStartAt() && $event->getStartAt() &&
                $this->event->getStartAt()->getTimestamp() == $event->getStartAt()->getTimestamp()) {
            $score++;
        } elseif ($this->event->getEndAt() && $event->getEndAt() &&
                $this->event->getEndAt()->getTimestamp() == $event->getEndAt()->getTimestamp()) {
            $score++;
        }
        
        if ($this->event->getGroupId() && $this->event->getGroupId() == $event->getGroupId()) {
            $score++;
        }
        if ($this->event->getUrl() && $this->getCanonicalURL($this->event->getUrl()) == $this->getCanonicalURL($event->getUrl())) {
            $score++;
        }
        if ($this->event->getTicketUrl() && $this->getCanonicalURL($this->event->getTicketUrl()) == $this->getCanonicalURL($event->getTicketUrl())) {
            $score++;
        }
        if ($this->event->getSummary()) {
            if ($this->event->getSummary() == $event->getSummary()) {
                $score++;
            } else {
                $bits1 = explode(" ", strtolower($this->event->getSummary()));
                $bits2 = explode(" ", strtolower($event->getSummary()));
                $flag = false;
                foreach ($bits1 as $bit) {
                    if ($bit && in_array($bit, $bits2) && !in_array($bit, $this->wordsNotToMatchInSummary)) {
                        $flag = true;
                    }
                }
                if ($flag) {
                    $score++;
                }
            }
        }
        if ($this->event->getVenueId() && $this->event->getVenueId() == $event->getVenueId()) {
            $score++;
        }
        if ($this->event->getAreaId() && $this->event->getAreaId() == $event->getAreaId()) {
            $score++;
        } elseif ($this->event->getAreaId() && $event->getArea() && $this->event->getAreaId() == $event->getArea()->getId()) {
            $score++;
        }


        return $score;
    }
    
    public function getCanonicalURL($url)
    {
        $data = parse_url($url);

        // For this purposes we're gonna treat http and https as the same.
        if (isset($data['scheme']) && strtolower($data['scheme']) == 'http') {
            $data['scheme'] = 'https';
        }

        $url = (isset($data['scheme']) && $data['scheme'] ? strtolower($data['scheme']).":":'http:') . '//';
        if ((isset($data['username']) && $data['username']) || (isset($data['password']) && $data['password'])) {
            $url .= $data['username'] . ":" . $data['password'] . "@";
        }
        $url .= (isset($data['host']) && $data['host'] ? strtolower($data['host']) : '').
            (isset($data['path']) && $data['path'] ? $data['path'] : '/')
            .'?'.(isset($data['query']) ? $data['query'] : '');
        return $url;
    }
}
