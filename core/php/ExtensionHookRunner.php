<?php


use models\AreaModel;
use models\GroupModel;
use models\SiteModel;
use models\VenueModel;
use models\UserAccountModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionHookRunner
{

    /** @var Application */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function beforeVenueSave(VenueModel $venue, UserAccountModel $user = null)
    {
        foreach ($this->app['config']->extensions as $extensionDir) {
            $this->app['extensions']->getExtensionByDir($extensionDir)->beforeVenueSave($venue, $user);
        }
    }

    public function beforeGroupSave(GroupModel $group, UserAccountModel $user = null)
    {
        foreach ($this->app['config']->extensions as $extensionDir) {
            $this->app['extensions']->getExtensionByDir($extensionDir)->beforeGroupSave($group, $user);
        }
    }

    public function beforeAreaSave(AreaModel $area, UserAccountModel $user = null)
    {
        foreach ($this->app['config']->extensions as $extensionDir) {
            $this->app['extensions']->getExtensionByDir($extensionDir)->beforeAreaSave($area, $user);
        }
    }

    public function afterSiteCreate(SiteModel $site, UserAccountModel $owner)
    {
        foreach ($this->app['config']->extensions as $extensionDir) {
            $this->app['extensions']->getExtensionByDir($extensionDir)->afterSiteCreate($site, $owner);
        }
    }

    public function afterUserAccountCreate(UserAccountModel $user)
    {
        foreach ($this->app['config']->extensions as $extensionDir) {
            $this->app['extensions']->getExtensionByDir($extensionDir)->afterUserAccountCreate($user);
        }
    }
}
