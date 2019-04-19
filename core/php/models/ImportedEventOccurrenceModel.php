<?php

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportedEventOccurrenceModel extends \models\ImportedEventModel
{
    public function setFromImportedEventModel(ImportedEventModel $importedEventModel)
    {
        $this->id = $importedEventModel->id;
        $this->import_id = $importedEventModel->import_id;
        $this->id_in_import = $importedEventModel->id_in_import;
        $this->title = $importedEventModel->title;
        $this->description = $importedEventModel->description;
        $this->start_at = $importedEventModel->start_at;
        $this->end_at = $importedEventModel->end_at;
        $this->timezone = $importedEventModel->timezone;
        $this->is_deleted = $importedEventModel->is_deleted;
        $this->url = $importedEventModel->url;
        $this->ticket_url = $importedEventModel->ticket_url;
        $this->lat = $importedEventModel->getLat();
        $this->lng = $importedEventModel->getLng();
        // This may seem odd to pass on .... surely for one occurence you don't care about the reoccurence rules?
        // but the ImportedEventOccurrenceModel will want to know if it from a reoccured series or not.
        $this->reoccur = $importedEventModel->reoccur;
    }
}
