<?php

namespace org\openacalendar\meetup;

use import\ImportHandlerBase;
use models\ImportedEventModel;
use models\ImportResultModel;
use repositories\ImportedEventRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportMeetupHandler extends ImportHandlerBase
{

    /**
     * It is important this runs before ImportMeetupHandler in Core, as in Core we try to map URL to ICAL data. We prefer using their API if we can.
     */
    public function getSortOrder()
    {
        return 2000;
    }

    protected $eventId;
    protected $groupName;

    public function canHandle()
    {
        global $app;
        
        $extension = $app['extensions']->getExtensionById('org.openacalendar.meetup');
        $accessToken =  $app['appconfig']->getValue($extension->getAppConfigurationDefinition('access_token'));

        $urlBits = parse_url($this->importRun->getRealURL());

        // If you are about to edit the code below stop right there!
        // TODO refactor it to use MeetupURLParser class instead and update that instead.
        if (in_array(strtolower($urlBits['host']), array('meetup.com','www.meetup.com')) && $accessToken) {
            $bits = explode("/", $urlBits['path']);
            
            if (count($bits) <= 3) {
                $this->groupName = $bits[1];
                return true;
            } elseif (count($bits) > 3 && $bits[2] == 'events') {
                $this->eventId = $bits[3];
                return true;
            }
        }
        
        return false;
    }

    protected $countNew;
    protected $countExisting;
    protected $countSaved;
    protected $countInPast;
    protected $countToFarInFuture;
    protected $countNotValid;

    
    public function handle()
    {
        $this->countNew = 0;
        $this->countExisting = 0;
        $this->countSaved = 0;
        $this->countInPast = 0;
        $this->countToFarInFuture = 0;
        $this->countNotValid = 0;

        $iurlr = new ImportResultModel();
        $iurlr->setIsSuccess(true);
        $iurlr->setMessage("Meetup data found");
        
        try {
            if ($this->eventId) {
                $meetupData = $this->getMeetupDataForEventID($this->eventId);
                if ($meetupData) {
                    $this->processMeetupData($meetupData);
                }
            } elseif ($this->groupName) {
                foreach ($this->getMeetupDatasForGroupname($this->groupName) as $meetupData) {
                    $this->processMeetupData($meetupData);
                }
            }
        } catch (ImportURLMeetupHandlerAPIError $err) {
            $iurlr->setIsSuccess(false);
            $iurlr->setMessage("Meetup API error: ". $err->getCode()." ".$err->getMessage());
        }

        $iurlr->setNewCount($this->countNew);
        $iurlr->setExistingCount($this->countExisting);
        $iurlr->setSavedCount($this->countSaved);
        $iurlr->setInPastCount($this->countInPast);
        $iurlr->setToFarInFutureCount($this->countToFarInFuture);
        $iurlr->setNotValidCount($this->countNotValid);
        return $iurlr;
    }
    
    protected function getMeetupDataForEventID($id)
    {
        global $app;
        
        $extension = $app['extensions']->getExtensionById('org.openacalendar.meetup');

        // Avoid Throttling
        sleep(1);

        try {
            $response = $this->app['extensions']->getExtensionById('org.openacalendar.meetup')->callV2($this->importRun->getGuzzle(), "/event/".$id."?fields=timezone&text_format=plain");

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                if (isset($data['code']) && $data['code']) {
                    if ($data['code'] == 'not_authorized') {
                        throw new ImportURLMeetupHandlerAPIError("API Key is not working", 1);
                    } elseif ($data['code'] == 'throttled') {
                        sleep(15);
                        throw new ImportURLMeetupHandlerAPIError("Our Access has been throttled", 1);
                    } elseif ($data['code'] == 'blocked') {
                        throw new ImportURLMeetupHandlerAPIError("Our Access has been blocked temporarily because throttling failed", 1);
                    }
                }
                return $data;
            } else {
                throw new ImportURLMeetupHandlerAPIError("Non 200 response - got ".$response->getStatusCode(), 1);
            }
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            throw new ImportURLMeetupHandlerAPIError("Got Exception " . $e->getMessage(), 1);
        }
    }
    
    protected function getMeetupDatasForGroupname($groupName)
    {
        global $app;
        
        $extension = $app['extensions']->getExtensionById('org.openacalendar.meetup');

        // Avoid Throttling
        sleep(1);

        $url = "/events/?fields=timezone&text_format=plain&group_urlname=".
            str_replace(array("&","?"), array("",""), $groupName);


        try {
            $response = $this->app['extensions']->getExtensionById('org.openacalendar.meetup')->callV2($this->importRun->getGuzzle(), $url);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                if (isset($data['code']) && $data['code']) {
                    if ($data['code'] == 'not_authorized') {
                        throw new ImportURLMeetupHandlerAPIError("API Key is not working", 1);
                    } elseif ($data['code'] == 'throttled') {
                        sleep(15);
                        throw new ImportURLMeetupHandlerAPIError("Our Access has been throttled", 1);
                    } elseif ($data['code'] == 'blocked') {
                        throw new ImportURLMeetupHandlerAPIError("Our Access has been blocked temporarily because throttling failed", 1);
                    }
                }
                if (isset($data['results']) && is_array($data['results'])) {
                    return $data['results'];
                } else {
                    throw new ImportURLMeetupHandlerAPIError("No Results where returned", 1);
                }
            } else {
                throw new ImportURLMeetupHandlerAPIError("Non 200 response - got " . $response->getStatusCode(), 1);
            }
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            throw new ImportURLMeetupHandlerAPIError("Got Exception " . $e->getMessage(), 1);
        }
    }

    protected function processMeetupData($meetupData)
    {
        $start = new \DateTime('', new \DateTimeZone('UTC'));
        $start->setTimestamp($meetupData['time'] / 1000);
        if (isset($meetupData['duration']) && $meetupData['duration']) {
            $end = new \DateTime('', new \DateTimeZone('UTC'));
            $end->setTimestamp($meetupData['time'] / 1000);
            $end->add(new \DateInterval("PT".($meetupData['duration'] / 1000)."S"));
        } else {
            $end = clone $start;
            $end->add(new \DateInterval("PT3H"));
        }
        if ($start && $end && $start <= $end) {
            $importedEventRepo = new \repositories\ImportedEventRepository($this->app);
            $id = "event_".$meetupData['id']."@meetup.com";
            $importedEvent = $importedEventRepo->loadByImportIDAndIdInImport($this->importRun->getImport()->getId(), $id);

            $changesToSave = false;
            if (!$importedEvent) {
                if ($meetupData['status'] != 'cancelled') {
                    ++$this->countNew;
                    $importedEvent = new ImportedEventModel();
                    $importedEvent->setIdInImport($id);
                    $importedEvent->setImportId($this->importRun->getImport()->getId());
                    $this->setImportedEventFromMeetupData($importedEvent, $meetupData);
                    $changesToSave = true;
                }
            } else {
                ++$this->countExisting;
                if ($meetupData['status'] == 'cancelled') {
                    if (!$importedEvent->getIsDeleted()) {
                        $importedEvent->setIsDeleted(true);
                        $changesToSave = true;
                    }
                } else {
                    $changesToSave = $this->setImportedEventFromMeetupData($importedEvent, $meetupData);
                    // if was deleted, undelete
                    if ($importedEvent->getIsDeleted()) {
                        $importedEvent->setIsDeleted(false);
                        $changesToSave = true;
                    }
                }
            }
            if ($changesToSave && $this->countSaved < $this->app['config']->importLimitToSaveOnEachRunImportedEvents) {
                ++$this->countSaved;

                if ($importedEvent->getId()) {
                    if ($importedEvent->getIsDeleted()) {
                        $importedEventRepo->delete($importedEvent);
                    } else {
                        $importedEventRepo->edit($importedEvent);
                    }
                } else {
                    $importedEventRepo->create($importedEvent);
                }
            }
            $this->importRun->markImportedEventSeen($importedEvent);
        }
    }
    
    protected function setImportedEventFromMeetupData(ImportedEventModel $importedEvent, $meetupData)
    {
        $changesToSave = false;
        if (isset($meetupData['description'])) {
            $description =  $meetupData['description'];
            if ($importedEvent->getDescription() != $description) {
                $importedEvent->setDescription($description);
                $changesToSave = true;
            }
        }
        $start = new \DateTime('', new \DateTimeZone('UTC'));
        $start->setTimestamp($meetupData['time'] / 1000);
        if (isset($meetupData['duration']) && $meetupData['duration']) {
            $end = new \DateTime('', new \DateTimeZone('UTC'));
            $end->setTimestamp($meetupData['time'] / 1000);
            $end->add(new \DateInterval("PT".($meetupData['duration'] / 1000)."S"));
        } else {
            $end = clone $start;
            $end->add(new \DateInterval("PT3H"));
        }
        if (!$importedEvent->getStartAt() || $importedEvent->getStartAt()->getTimeStamp() != $start->getTimeStamp()) {
            $importedEvent->setStartAt($start);
            $changesToSave = true;
        }
        if (!$importedEvent->getEndAt() || $importedEvent->getEndAt()->getTimeStamp() != $end->getTimeStamp()) {
            $importedEvent->setEndAt($end);
            $changesToSave = true;
        }
        if ($importedEvent->getTitle() != $meetupData['name']) {
            $importedEvent->setTitle($meetupData['name']);
            $changesToSave = true;
        }
        if ($importedEvent->getUrl() != $meetupData['event_url']) {
            $importedEvent->setUrl($meetupData['event_url']);
            $changesToSave = true;
        }
        if ($importedEvent->getTimezone() != $meetupData['timezone']) {
            $importedEvent->setTimezone($meetupData['timezone']);
            $changesToSave = true;
        }
        if ($importedEvent->getTicketUrl() != $meetupData['event_url']) {
            $importedEvent->setTicketUrl($meetupData['event_url']);
            $changesToSave = true;
        }
        if (isset($meetupData['venue']) && isset($meetupData['venue']['lon']) && $meetupData['venue']['lon'] != $importedEvent->getLng()) {
            $importedEvent->setLng($meetupData['venue']['lon']);
            $changesToSave = true;
        }
        if (isset($meetupData['venue']) && isset($meetupData['venue']['lat']) && $meetupData['venue']['lat'] != $importedEvent->getLat()) {
            $importedEvent->setLat($meetupData['venue']['lat']);
            $changesToSave = true;
        }
        return $changesToSave;
    }
}




class ImportURLMeetupHandlerAPIError extends \Exception
{
}
