<?php
namespace import;

use models\ImportModel;
use models\ImportResultModel;
use repositories\builders\ImportedEventRepositoryBuilder;
use repositories\EventRecurSetRepository;
use repositories\ImportedEventRepository;
use repositories\ImportResultRepository;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportRunner
{

    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function go(ImportModel $importModel)
    {
        $importRun = new ImportRun($this->app, $importModel);
        if ($this->runHandlersSaveResult($importRun)) {
            $this->runImportedEventsToEvents($importRun);
        }
    }

    protected function runHandlersSaveResult(ImportRun $importRun)
    {
        $iurlrRepo = new ImportResultRepository($this->app);
        $handlers = array();
        
        // Get
        foreach ($this->app['extensions']->getExtensionsIncludingCore() as $extension) {
            foreach ($extension->getImportHandlers() as $handler) {
                $handlers[] = $handler;
            }
        }
        
        // Sort
        usort($handlers, function ($a, $b) {
            if ($a->getSortOrder() == $b->getSortOrder()) {
                return 0;
            } elseif ($a->getSortOrder() > $b->getSortOrder()) {
                return 1;
            } elseif ($a->getSortOrder() < $b->getSortOrder()) {
                return -1;
            }
        });

        // Run
        foreach ($handlers as $handler) {
            $handler->setImportRun($importRun);
            if ($handler->canHandle()) {
                if ($handler->isStopAfterHandling()) {
                    $iurlr = $handler->handle();
                    $iurlr->setImportId($importRun->getImport()->getId());
                    $iurlrRepo->create($iurlr);
                    return $iurlr->getIsSuccess();
                } else {
                    $handler->handle();
                }
            }
        }

        // Log that couldn't handle feed
        $iurlr = new ImportResultModel();
        $iurlr->setImportId($importRun->getImport()->getId());
        $iurlr->setIsSuccess(false);
        $iurlr->setMessage("Did not recognise data");
        $iurlrRepo->create($iurlr);
        return false;
    }

    protected function runImportedEventsToEvents(ImportRun $importRun)
    {
        $eventRecurSetRepo = new EventRecurSetRepository($this->app);
        $importedEventRepo = new ImportedEventRepository($this->app);

        $importedEventOccurrenceToEvent = new ImportedEventOccurrenceToEvent($this->app, $importRun);
        $saved = 0;

        $importedEventsRepo = new ImportedEventRepositoryBuilder($this->app);
        $importedEventsRepo->setImport($importRun->getImport());
        $importedEventsRepo->setIncludeDeleted(true);
        foreach ($importedEventsRepo->fetchAll() as $importedEvent) {
            if (!$importRun->wasImportedEventSeen($importedEvent)) {
                // So we have this event in our store, but it wasn't seen in the last import. Mark it deleted!
                if (!$importedEvent->getIsDeleted()) {
                    $importedEvent->setIsDeleted(true);
                    $importedEventRepo->delete($importedEvent);
                }
            }

            $importedEventToImportedEventOccurrences = new ImportedEventToImportedEventOccurrences($this->app, $importedEvent);

            if ($importedEventToImportedEventOccurrences->getToMultiples()) {
                $eventRecurSet = $importedEvent != null ? $eventRecurSetRepo->getForImportedEvent($importedEvent) : null;
                $importedEventOccurrenceToEvent->setEventRecurSet($eventRecurSet, true);
            } else {
                $importedEventOccurrenceToEvent->setEventRecurSet(null, false);
            }

            foreach ($importedEventToImportedEventOccurrences->getImportedEventOccurrences() as $importedEventOccurrence) {
                if ($importedEventOccurrence->getEndAt()->getTimeStamp() < $this->app['timesource']->time()) {
                    // TODO log this somewhere?
                } elseif ($importedEventOccurrence->getStartAt()->getTimeStamp() > $this->app['timesource']->time()+$this->app['config']->importAllowEventsSecondsIntoFuture) {
                    // TODO log this somewhere?
                } elseif ($saved < $this->app['config']->importLimitToSaveOnEachRunEvents) {
                    if ($importedEventOccurrenceToEvent->run($importedEventOccurrence)) {
                        $saved++;
                    }
                }
            }
        }

        $importedEventOccurrenceToEvent->deleteEventsNotSeenAfterRun();
    }
}
