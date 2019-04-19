<?php


namespace repositories;

use models\SiteModel;
use models\UserAccountModel;
use Silex\Application;

/**
 *
 * Note this only saves! All information is loaded thru normal Site repository and model.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteProfileMediaRepository
{


    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function createOrEdit(SiteModel $site, UserAccountModel $user)
    {
        $createdat = $this->app['timesource']->getFormattedForDataBase();
        
        try {
            $this->app['db']->beginTransaction();

            $stat = $this->app['db']->prepare("SELECT * FROM site_profile_media_information WHERE site_id=:site_id");
            $stat->execute(array(
                'site_id'=>$site->getId(),
            ));
            if ($stat->rowCount() == 1) {
                $stat = $this->app['db']->prepare("UPDATE site_profile_media_information SET logo_media_id=:logo_media_id ".
                        " WHERE site_id=:site_id");
            } else {
                $stat = $this->app['db']->prepare("INSERT INTO site_profile_media_information (site_id, logo_media_id) ".
                    " VALUES (:site_id, :logo_media_id)");
            }
            $stat->execute(array(
                    'logo_media_id'=>$site->getLogoMediaId(),
                    'site_id'=>$site->getId(),
                ));
            
            $stat = $this->app['db']->prepare("INSERT INTO site_profile_media_history (site_id, logo_media_id, user_account_id, created_at) ".
                    " VALUES (:site_id, :logo_media_id, :user_account_id, :created_at)");
            $stat->execute(array(
                    'site_id'=>$site->getId(),
                    'logo_media_id'=>$site->getLogoMediaId(),
                    'created_at'=>  $createdat,
                    'user_account_id'=>$user->getId(),
                ));
            $data = $stat->fetch();
            
            
            $this->app['db']->commit();
        } catch (Exception $e) {
            $this->app['db']->rollBack();
        }
    }
}
