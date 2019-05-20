<?php



namespace repositories;

use models\MediaModel;
use models\UserAccountModel;
use models\EventModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class MediaInEventRepository
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    public function add(MediaModel $media, EventModel $event, UserAccountModel $user)
    {

        
        // check event not already in list
        $stat = $this->app['db']->prepare("SELECT * FROM media_in_event WHERE event_id=:event_id AND ".
                " media_id=:media_id AND removed_at IS NULL ");
        $stat->execute(array(
            'event_id'=>$event->getId(),
            'media_id'=>$media->getId(),
        ));
        if ($stat->rowCount() > 0) {
            return;
        }
        
        // Add!
        $stat = $this->app['db']->prepare("INSERT INTO media_in_event (event_id,media_id,added_by_user_account_id,added_at,addition_approved_at) ".
                "VALUES (:event_id,:media_id,:added_by_user_account_id,:added_at,:addition_approved_at)");
        $stat->execute(array(
            'event_id'=>$event->getId(),
            'media_id'=>$media->getId(),
            'added_by_user_account_id'=>$user->getId(),
            'added_at'=>  $this->app['timesource']->getFormattedForDataBase(),
            'addition_approved_at'=>  $this->app['timesource']->getFormattedForDataBase(),
        ));

        $this->app['messagequeproducerhelper']->send('org.openacalendar', 'MediaInEventSaved', array('media_id'=>$media->getId(),'event_id'=>$event->getId()));
    }


    public function remove(MediaModel $media, EventModel $event, UserAccountModel $user)
    {
        $stat = $this->app['db']->prepare("UPDATE media_in_event SET removed_by_user_account_id=:removed_by_user_account_id,".
                " removed_at=:removed_at, removal_approved_at=:removal_approved_at WHERE ".
                " event_id=:event_id AND media_id=:media_id AND removed_at IS NULL ");
        $stat->execute(array(
                'event_id'=>$event->getId(),
                'media_id'=>$media->getId(),
                'removed_at'=>  $this->app['timesource']->getFormattedForDataBase(),
                'removal_approved_at'=>  $this->app['timesource']->getFormattedForDataBase(),
                'removed_by_user_account_id'=>$user->getId(),
            ));

        $this->app['messagequeproducerhelper']->send('org.openacalendar', 'MediaInEventSaved', array('media_id'=>$media->getId(),'event_id'=>$event->getId()));
    }
}
