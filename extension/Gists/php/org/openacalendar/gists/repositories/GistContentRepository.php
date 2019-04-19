<?php

namespace org\openacalendar\gists\repositories;

use models\SiteModel;
use models\UserAccountModel;
use org\openacalendar\gists\models\GistContentModel;
use org\openacalendar\gists\models\GistModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class GistContentRepository
{

    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function createAtEnd(GistContentModel $gistContentModel, GistModel $gistModel, UserAccountModel $creator)
    {
        try {
            $this->app['db']->beginTransaction();

            $stat = $this->app['db']->prepare("SELECT max(sort) AS c FROM gist_content_information WHERE gist_id=:gist_id");
            $stat->execute(array('gist_id'=>$gistModel->getId()));
            $data = $stat->fetch();
            $gistContentModel->setSort($data['c'] + 1);

            $stat = $this->app['db']->prepare("INSERT INTO gist_content_information (gist_id, sort, event_id,group_id,area_id,venue_id,content_title,content_text,created_at) ".
                "VALUES (:gist_id, :sort, :event_id,:group_id,:area_id,:venue_id,:content_title,:content_text,:created_at) RETURNING id");
            $stat->execute(array(
                'gist_id'=>$gistModel->getId(),
                'sort'=>$gistContentModel->getSort(),
                'event_id'=>$gistContentModel->getEventId(),
                'group_id'=>$gistContentModel->getGroupId(),
                'area_id'=>$gistContentModel->getAreaId(),
                'venue_id'=>$gistContentModel->getVenueId(),
                'content_title'=>substr($gistContentModel->getContentTitle(), 0, VARCHAR_COLUMN_LENGTH_USED),
                'content_text'=>$gistContentModel->getContentText(),
                'created_at'=>$this->app['timesource']->getFormattedForDataBase()
            ));
            $data = $stat->fetch();
            $gistContentModel->setId($data['id']);


            $this->app['db']->commit();
        } catch (Exception $e) {
            $this->app['db']->rollBack();
        }
    }
}
