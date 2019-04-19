<?php


namespace usernotifications\models;

use models\GroupModel;
use models\ImportModel;
use repositories\ImportRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLExpiredUserNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'ImportURLExpired';
    }
    
    public function setImport(ImportModel $import)
    {
        $this->data['import'] = $import->getId();
    }


    public function setGroup(GroupModel $group)
    {
        $this->data['group'] = $group->getId();
    }

    /** @var GroupModel  **/
    public $group;

    /** @var ImportModel  **/
    public $import;
    
    private function loadImportURLIfNeeded()
    {
        global $app;
        if (!$this->import && property_exists($this->data, 'import') && $this->data->import) {
            $repo = new ImportRepository($app);
            $this->import = $repo->loadById($this->data->import);
        }
    }
    
    public function getNotificationText()
    {
        $this->loadImportURLIfNeeded();
        // Checking $this->import exists is related to #261 - bad data might exist that doesn't have this set
        // The change from importurl to import in https://github.com/OpenACalendar/OpenACalendar-Web-Core/issues/520 will also cause issues.
        return "An Importer has expired: ".($this->import ? $this->import->getTitle() : null);
    }
    
    public function getNotificationURL()
    {
        global $CONFIG;
        $this->loadImportURLIfNeeded();
        return $CONFIG->getWebSiteDomainSecure($this->site->getSlug()).'/import/'.$this->import->getSlug();
    }
}
