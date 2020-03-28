<?php

use import\ImportNotUsHandler;
use import\ImportMeetupHandler;
use import\ImportEventbriteHandler;
use import\ImportLanyrdHandler;
use import\ImportICalHandler;
use models\SiteModel;
use models\UserAccountModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionCore extends BaseExtension
{
    public function getId()
    {
        return 'org.openacalendar';
    }

    public function getTitle()
    {
        return 'Core';
    }

    public function getUserPermissions()
    {
        return array('CREATE_SITE','CALENDAR_CHANGE','CALENDAR_ADMINISTRATE',
            'AREAS_CHANGE','EVENTS_CHANGE','GROUPS_CHANGE','IMPORTURL_CHANGE','TAGS_CHANGE','VENUES_CHANGE','MEDIAS_CHANGE');
    }

    public function getUserPermission($key)
    {
        if ($key == 'CREATE_SITE') {
            return new \userpermissions\CreateSiteUserPermission();
        } elseif ($key == 'CALENDAR_CHANGE') {
            return new \userpermissions\CalendarChangeUserPermission();
        } elseif ($key == 'CALENDAR_ADMINISTRATE') {
            return new \userpermissions\CalendarAdministrateUserPermission();
        } elseif ($key == 'AREAS_CHANGE') {
            return new \userpermissions\AreasChangeUserPermission();
        } elseif ($key == 'EVENTS_CHANGE') {
            return new \userpermissions\EventsChangeUserPermission();
        } elseif ($key == 'GROUPS_CHANGE') {
            return new \userpermissions\GroupsChangeUserPermission();
        } elseif ($key == 'IMPORTURL_CHANGE') {
            return new \userpermissions\ImportChangeUserPermission();
        } elseif ($key == 'TAGS_CHANGE') {
            return new \userpermissions\TagsChangeUserPermission();
        } elseif ($key == 'VENUES_CHANGE') {
            return new \userpermissions\VenuesChangeUserPermission();
        } elseif ($key == 'MEDIAS_CHANGE') {
            return new \userpermissions\MediasChangeUserPermission();
        }
    }

    public function getUserNotificationTypes()
    {
        return array('UpcomingEvents','UserWatchesGroupPrompt','UserWatchesGroupNotify',
            'UserWatchesSiteNotify','UserWatchesSiteGroupPrompt','UserWatchesSitePrompt',
            'ImportURLExpired','UserRequestsAccessNotifyAdmin','UserWatchesNotify');
    }
    
    public function getUserNotificationType($type)
    {
        if ($type == 'UpcomingEvents') {
            return new usernotifications\types\UpcomingEventsUserNotificationType();
        } elseif ($type == 'UserWatchesGroupPrompt') {
            return new usernotifications\types\UserWatchesGroupPromptNotificationType();
        } elseif ($type == 'UserWatchesNotify') {
            return new usernotifications\types\UserWatchesNotifyNotificationType();
        } elseif ($type == 'UserWatchesGroupNotify') {
            // deprecated !!!!!!!
            return new usernotifications\types\UserWatchesGroupNotifyNotificationType();
        } elseif ($type == 'UserWatchesSiteNotify') {
            // deprecated !!!!!!!
            return new usernotifications\types\UserWatchesSiteNotifyNotificationType();
        } elseif ($type == 'UserWatchesSiteGroupPrompt') {
            return new usernotifications\types\UserWatchesSiteGroupPromptNotificationType();
        } elseif ($type == 'UserWatchesSitePrompt') {
            return new usernotifications\types\UserWatchesSitePromptNotificationType();
        } elseif ($type == 'ImportURLExpired') {
            return new usernotifications\types\ImportURLExpiredUserNotificationType();
        } elseif ($type == 'UserRequestsAccessNotifyAdmin') {
            return new usernotifications\types\UserRequestsAccessNotifyAdminNotificationType();
        } else {
            return null;
        }
    }
    
    
    public function getUserNotificationPreferenceTypes()
    {
        return array('WatchPrompt','WatchNotify','UpcomingEvents','WatchImportExpired');
    }
    
    public function getUserNotificationPreference($type)
    {
        if ($type == 'WatchPrompt') {
            return new usernotifications\preferences\WatchPromptNotificationPreference();
        } elseif ($type == 'WatchNotify') {
            return new usernotifications\preferences\WatchNotifyNotificationPreference();
        } elseif ($type == 'WatchImportExpired') {
            return new usernotifications\preferences\WatchImportExpiredNotificationPreference();
        } elseif ($type == 'UpcomingEvents') {
            return new usernotifications\preferences\UpcomingEventsNotificationPreference();
        }
        return null;
    }
    
    public function getImportHandlers()
    {
        return array(
            // Common Sense Handler
            new ImportNotUsHandler($this->app),
            // rewrite URL to ICAL handlers
            new ImportMeetupHandler($this->app),
            new ImportEventbriteHandler($this->app),
            new ImportLanyrdHandler($this->app),
            // handlers!
            new ImportICalHandler($this->app),
        );
    }

    public function clearAppCache()
    {
        $cacheDir = APP_ROOT_DIR."/cache/";
        foreach (glob($cacheDir."/templates.cli/*/*.php") as $file) {
            unlink($file);
        }
        foreach (glob($cacheDir."/templates.web/*/*.php") as $file) {
            unlink($file);
        }
    }

    public function getTasks()
    {
        return array(
            new \tasks\SendUserWatchesNotifyTask($this->app),
            new \tasks\UpdateVenueFutureEventsCacheTask($this->app),
            new \tasks\UpdateGroupFutureEventsCacheTask($this->app),
            new \tasks\UpdateAreaFutureEventsCacheTask($this->app),
            new \tasks\UpdateAreaBoundsCacheTask($this->app),
            new \tasks\UpdateAreaParentCacheTask($this->app),
            new \tasks\UpdateSiteCacheTask($this->app),
            new \tasks\UpdateAreaHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateEventHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateGroupHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateMediaHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateImportHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateSiteHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateTagHistoryChangeFlagsTask($this->app),
            new \tasks\UpdateVenueHistoryChangeFlagsTask($this->app),
            // CORONAVIRUS
            // TEMP DISABLE
            // We don't want to send emails to people saying "Do you know of more events?"
            // Cos they won't :-(
            // BEFORE WE REENABLE - we need to double check how these will behave, will they flood people with emails?
            //new \tasks\SendUserWatchesSitePromptEmailsTask($this->app),
            //new \tasks\SendUserWatchesSiteGroupPromptEmailsTask($this->app),
            //new \tasks\SendUserWatchesGroupPromptEmailsTask($this->app),
            new \tasks\SendUpcomingEventsForUsersTask($this->app),
            new \tasks\RunImportsTask($this->app),
            new \tasks\DeleteOldTaskLogsTask($this->app),
            new \tasks\UpdateCountryInSiteFutureEventsCacheTask($this->app),
            new \tasks\DeleteOldHistoryIps($this->app),
        );
    }

    /**
     * @return array BaseUserWatchesNotifyContent
     */
    public function getUserNotifyContents(SiteModel $site, UserAccountModel $userAccountModel)
    {
        $out = array();

        $userWatchesSiteRepo = new \repositories\UserWatchesSiteRepository($this->app);
        $data = $userWatchesSiteRepo->getUserNotifyContentForSiteAndUser($site, $userAccountModel);
        if ($data) {
            // no point carrying on; someone watching a site overrides any data contained within
            return array($data);
        }

        $siteFeatureRepo = new \repositories\SiteFeatureRepository($this->app);
        if ($siteFeatureRepo->doesSiteHaveFeatureByExtensionAndId($site, 'org.openacalendar', 'Group')) {
            $userWatchesGroupRepo = new \repositories\UserWatchesGroupRepository($this->app);
            $data = $userWatchesGroupRepo->getUserNotifyContentForSiteAndUser($site, $userAccountModel);
            if ($data) {
                $out = array_merge($out, $data);
            }
        }


        $userWatchesAreaRepo = new \repositories\UserWatchesAreaRepository($this->app);
        $data = $userWatchesAreaRepo->getUserNotifyContentForSiteAndUser($site, $userAccountModel);
        if ($data) {
            $out = array_merge($out, $data);
        }


        return $out;
    }


    public function getNewsFeedModel($interfaceHistoryModel, SiteModel $siteModel)
    { // TODO can't set type InterfaceHistoryModel!!!!!!!
        if ($interfaceHistoryModel instanceof \models\AreaHistoryModel) {
            return new \newsfeedmodels\AreaHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        } elseif ($interfaceHistoryModel instanceof \models\EventHistoryModel) {
            return new \newsfeedmodels\EventHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        } elseif ($interfaceHistoryModel instanceof \models\GroupHistoryModel) {
            return new \newsfeedmodels\GroupHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        } elseif ($interfaceHistoryModel instanceof \models\ImportHistoryModel) {
            return new \newsfeedmodels\ImportHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        } elseif ($interfaceHistoryModel instanceof \models\TagHistoryModel) {
            return new \newsfeedmodels\TagHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        } elseif ($interfaceHistoryModel instanceof \models\VenueHistoryModel) {
            return new \newsfeedmodels\VenueHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        } elseif ($interfaceHistoryModel instanceof \models\MediaHistoryModel) {
            return new \newsfeedmodels\MediaHistoryNewsFeedModel($interfaceHistoryModel, $siteModel);
        }
    }

    public function getSeriesReports()
    {
        return array(
            new reports\seriesreports\UsersWithNotificationsSeriesReport($this->app),
            new reports\seriesreports\UsersWithEventEditsSeriesReport($this->app),
            new reports\seriesreports\UsersWithEventsEditedSeriesReport($this->app),
            new reports\seriesreports\GroupsWithUsersWatching($this->app),
            new reports\seriesreports\AreasWithUsersDirectlyWatching($this->app),
            new reports\seriesreports\EventsStartAtByImportedOrNotSeriesReport($this->app),
        );
    }

    public function getValueReports()
    {
        return array(
            new reports\valuereports\EventsCreatedReport($this->app),
            new reports\valuereports\GroupsCreatedReport($this->app),
            new reports\valuereports\VenuesCreatedReport($this->app),
            new reports\valuereports\UsersCreatedReport($this->app),
            new reports\valuereports\EventsEditedReport($this->app),
            new reports\valuereports\GroupsEditedReport($this->app),
            new reports\valuereports\VenuesEditedReport($this->app),
        );
    }

    public function getSiteFeatures(\models\SiteModel $siteModel = null)
    {
        return array(
            new sitefeatures\GroupFeature(),
            new sitefeatures\ImporterFeature(),
            new sitefeatures\TagFeature(),
            new sitefeatures\PhysicalEventsFeature(),
            new sitefeatures\MapFeature(),
            new sitefeatures\VirtualEventsFeature(),
            new sitefeatures\EditCommentsFeature(),
        );
    }

    /** @return InterfaceEventCustomFieldType */
    public function getEventCustomFieldByType($type)
    {
        if ($type == 'TextSingleLine') {
            return new \customfieldtypes\event\TextSingleLineEventCustomFieldType();
        } elseif ($type == 'TextMultiLine') {
            return new \customfieldtypes\event\TextMultiLineEventCustomFieldType();
        }
    }


    public function getMessageQueWorkers()
    {
        return array(
            new \messagequeworkers\RunImportNowMessageQueWorker($this->app),
        );
    }

    public function canPurgeUser(UserAccountModel $userAccountModel)
    {

        // If user account was closed by sysadmin we leave alone
        if ($userAccountModel->getIsClosedBySysAdmin()) {
            return false;
        }

        // Ok, we are happy.
        return true;
    }
}
