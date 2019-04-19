<?php

namespace org\openacalendar\gists\repositories;

use models\SiteModel;
use models\UserAccountModel;
use org\openacalendar\gists\models\GistModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class GistRepository
{

    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create(GistModel $gistModel, SiteModel $site, UserAccountModel $creator)
    {
        try {
            $this->app['db']->beginTransaction();


            $keyLength = 5;
            $gistModel->setSlug(createKey(1, $keyLength));
            while ($this->isSlugUsedInsite($gistModel->getSlug(), $site)) {
                $keyLength = min(255, $keyLength+5);
                $gistModel->setSlug(createKey(1, $keyLength));
            }

            $stat = $this->app['db']->prepare("INSERT INTO gist_information (site_id, slug, title,created_at,is_deleted) ".
                "VALUES (:site_id, :slug, :title, :created_at,'0') RETURNING id");
            $stat->execute(array(
                'site_id'=>$site->getId(),
                'slug'=>$gistModel->getSlug(),
                'title'=>substr($gistModel->getTitle(), 0, VARCHAR_COLUMN_LENGTH_USED),
                'created_at'=>$this->app['timesource']->getFormattedForDataBase()
            ));
            $data = $stat->fetch();
            $gistModel->setId($data['id']);

            $stat = $this->app['db']->prepare("INSERT INTO user_in_gist_information (user_account_id,gist_id,is_owner,created_at) ".
                " VALUES (:user_account_id,:gist_id,:is_owner,:created_at) ");
            $stat->execute(array(
                'user_account_id'=>$creator->getId(),
                'gist_id'=>$gistModel->getId(),
                'is_owner'=>'1',
                'created_at'=>$this->app['timesource']->getFormattedForDataBase(),
            ));

            $this->app['db']->commit();
        } catch (Exception $e) {
            $this->app['db']->rollBack();
        }
    }

    protected function isSlugUsedInsite($slug, SiteModel $siteModel)
    {
        $stat = $this->app['db']->prepare("SELECT id FROM gist_information WHERE site_id=:site_id AND slug=:slug");
        $stat->execute(array('site_id'=>$siteModel->getId(),'slug'=>$slug));
        return $stat->rowCount() > 0;
    }

    public function loadBySlug(SiteModel $site, $slug)
    {
        $stat = $this->app['db']->prepare("SELECT gist_information.* FROM gist_information WHERE slug =:slug AND site_id =:sid");
        $stat->execute(array( 'sid'=>$site->getId(), 'slug'=>$slug ));
        if ($stat->rowCount() > 0) {
            $gist = new GistModel();
            $gist->setFromDataBaseRow($stat->fetch());
            return $gist;
        }
    }

    public function canUserEditGist(UserAccountModel $user, GistModel $gistModel)
    {
        if (!$user) {
            return false;
        }
        $stat = $this->app['db']->prepare("SELECT * FROM user_in_gist_information WHERE user_account_id=:uid AND gist_id=:gid".
            " AND (is_editor = '1' OR is_owner = '1')");
        ;
        $stat->execute(array('uid'=>$user->getId(), 'gid'=>$gistModel->getId()));
        return ($stat->rowCount() > 0);
    }
}
