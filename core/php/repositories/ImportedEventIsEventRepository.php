<?php


namespace repositories;

use models\ImportedEventModel;
use models\EventModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class ImportedEventIsEventRepository
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function createLink(ImportedEventModel $importedEvent, EventModel $event)
    {
        $stat = $this->app['db']->prepare("INSERT INTO imported_event_is_event (imported_event_id, event_id, created_at) ".
                "VALUES (:imported_event_id, :event_id, :created_at)");
        $stat->execute(array(
            'imported_event_id'=>$importedEvent->getId(),
            'event_id'=>$event->getId(),
            'created_at'=>$this->app['timesource']->getFormattedForDataBase(),
        ));
    }
}
