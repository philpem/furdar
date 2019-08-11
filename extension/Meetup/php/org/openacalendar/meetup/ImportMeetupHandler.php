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

    protected $meetupEventId;
    protected $meetupGroupName;

    public function canHandle()
    {
        $extension = $this->app['extensions']->getExtensionById('org.openacalendar.meetup');
        if ($this->app['appconfig']->getValue($extension->getAppConfigurationDefinition('access_token'))) {
            $meetupURLParser = new MeetupURLParser($this->importRun->getRealURL());
            $this->meetupGroupName = $meetupURLParser->getGroupName();
            $this->meetupEventId = $meetupURLParser->getEventId();
            if ($this->meetupGroupName || $this->meetupEventId) {
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
            if ($this->meetupEventId) {
                $meetupData = $this->getMeetupDataForEventID($this->meetupGroupName, $this->meetupEventId);
                if ($meetupData) {
                    $this->processMeetupData($meetupData);
                }
            } elseif ($this->meetupGroupName) {
                foreach ($this->getMeetupDatasForGroupname($this->meetupGroupName) as $meetupData) {
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
    
    protected function getMeetupDataForEventID($groupName, $id)
    {
        // Avoid Throttling
        sleep(1);

        $url = "/".str_replace(array("&","?"), array("",""), $groupName) ."/events/". $id ."/?fields=plain_text_no_images_description";

        try {
            $response = $this->app['extensions']->getExtensionById('org.openacalendar.meetup')->callMeetupAPI($this->importRun->getGuzzle(), $url);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
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
        // Avoid Throttling
        sleep(1);

        $url = "/".str_replace(array("&","?"), array("",""), $groupName) ."/events/?fields=plain_text_no_images_description";

        try {
            $response = $this->app['extensions']->getExtensionById('org.openacalendar.meetup')->callMeetupAPI($this->importRun->getGuzzle(), $url);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                if (is_array($data)) {
                    return $data;
                } else {
                    throw new ImportURLMeetupHandlerAPIError("No Results were returned", 1);
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
        if (isset($meetupData['plain_text_no_images_description'])) {
            $description =  $meetupData['plain_text_no_images_description'];
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
        if ($importedEvent->getUrl() != $meetupData['link']) {
            $importedEvent->setUrl($meetupData['link']);
            $changesToSave = true;
        }
        if ($importedEvent->getTimezone() != $meetupData['group']['timezone']) {
            $importedEvent->setTimezone($meetupData['group']['timezone']);
            $changesToSave = true;
        }
        if ($importedEvent->getTicketUrl() != $meetupData['link']) {
            $importedEvent->setTicketUrl($meetupData['link']);
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
