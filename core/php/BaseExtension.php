<?php

use models\AreaModel;
use models\GroupModel;
use models\SiteModel;
use models\VenueModel;
use models\UserAccountModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseExtension
{

    /** @var  Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    abstract public function getId();
    
    
    abstract public function getTitle();
    
    public function getDescription()
    {
        return null;
    }



    public function beforeVenueSave(VenueModel $venue, UserAccountModel $user = null)
    {
    }

    public function beforeGroupSave(GroupModel $venue, UserAccountModel $user = null)
    {
    }

    public function beforeAreaSave(AreaModel $venue, UserAccountModel $user = null)
    {
    }


    public function addDetailsToVenue(VenueModel $venue)
    {
    }

    /**
     * @param SiteModel $site
     * @param UserAccountModel $owner User who owns site. This is usually, but may not be, the user who performed the action.
     */
    public function afterSiteCreate(SiteModel $site, UserAccountModel $owner)
    {
    }

    /**
     */
    public function afterUserAccountCreate(UserAccountModel $userAccountModel)
    {
    }

    public function getUserNotificationTypes()
    {
        return array();
    }

    public function getUserPermissions()
    {
        return array();
    }

    public function getUserPermission($key)
    {
        return null;
    }
    
    public function getUserNotificationType($type)
    {
        return null;
    }
    
    public function getUserNotificationPreferenceTypes()
    {
        return array();
    }
    
    public function getUserNotificationPreference($type)
    {
        return null;
    }
    
    public function getAppConfigurationDefinitions()
    {
        return array();
    }

    
    public function getAppConfigurationDefinition($key)
    {
        foreach ($this->getAppConfigurationDefinitions() as $def) {
            if ($def->getKey() == $key) {
                return $def;
            }
        }
    }
    
    public function getImportHandlers()
    {
        return array();
    }

    /**
     * This should be called on server update, install update, code update, config change or extension activated/deactivated
     */
    public function clearAppCache()
    {
    }

    /**
     * Called to get template variables.
     *
     * Note this in only called in specific situations; when the system is about to send an email.
     *
     * @param \models\SiteModel $siteModel
     * @return array
     */
    public function getTemplateVariables(\models\SiteModel $siteModel = null)
    {
        return array();
    }

    /**
     * @return array SysAdminLink return array of SysAdminLink new array('url'=>, 'title'=>)
     */
    public function getSysAdminLinks()
    {
        return array();
    }

    public function getAddContentToEventShowPages($parameters)
    {
        return array();
    }

    /**
     * @return array BaseTask
     */
    public function getTasks()
    {
        return array();
    }


    /**
     * @return array BaseUserWatchesNotifyContent
     */
    public function getUserNotifyContents(SiteModel $site, UserAccountModel $userAccountModel)
    {
        return array();
    }

    /** @return InterfaceNewsFeedModel */
    public function getNewsFeedModel($interfaceHistoryModel, SiteModel $siteModel)
    { // @TODO InterfaceHistoryModel type!!!!!!
        return null;
    }

    public function getHistoryRepositoryBuilderData(\repositories\builders\config\HistoryRepositoryBuilderConfig $historyRepositoryBuilderConfig)
    {
        return array();
    }


    public function makeSureHistoriesAreCorrect($interfaceHistoryModel)
    {  // @TODO InterfaceHistoryModel type!!!!!!
    }


    public function getSiteFeatures(\models\SiteModel $siteModel = null)
    {
        return array();
    }

    /** @return InterfaceEventCustomFieldType */
    public function getEventCustomFieldByType($type)
    {
    }

    public function getSeriesReports()
    {
        return array();
    }

    public function getValueReports()
    {
        return array();
    }

    public function getMessageQueWorkers()
    {
        return array();
    }

    public function getImportURLRecommendations(\import\ImportURLRecommendationDataToCheck $dataToCheck)
    {
        return array();
    }

    public function canPurgeUser(UserAccountModel $userAccountModel)
    {
        return true;
    }

    public function purgeUser(UserAccountModel $userAccountModel)
    {
    }
}
