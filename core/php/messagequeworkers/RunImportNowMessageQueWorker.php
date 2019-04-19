<?php

namespace messagequeworkers;

use repositories\ImportRepository;
use tasks\RunImportsTask;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class RunImportNowMessageQueWorker extends BaseMessageQueWorker
{
    public function process(string $extension, string $type, $data)
    {
        if ($extension == 'org.openacalendar' && $type == 'ImportSaved') {
            $importrepo = new ImportRepository($this->app);
            $import = $importrepo->loadById($data['import_id']);

            if ($import) {
                $task = new RunImportsTask($this->app);
                $task->runImport($import);

                return true;
            }
        }
        return false;
    }
}
