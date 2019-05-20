<?php



namespace repositories;

use models\MediaModel;
use models\UserAccountModel;
use models\VenueModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class MediaInVenueRepository
{


    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function add(MediaModel $media, VenueModel $venue, UserAccountModel $user)
    {

        
        // check event not already in list
        $stat = $this->app['db']->prepare("SELECT * FROM media_in_venue WHERE venue_id=:venue_id AND ".
                " media_id=:media_id AND removed_at IS NULL ");
        $stat->execute(array(
            'venue_id'=>$venue->getId(),
            'media_id'=>$media->getId(),
        ));
        if ($stat->rowCount() > 0) {
            return;
        }
        
        // Add!
        $stat = $this->app['db']->prepare("INSERT INTO media_in_venue (venue_id,media_id,added_by_user_account_id,added_at,addition_approved_at) ".
                "VALUES (:venue_id,:media_id,:added_by_user_account_id,:added_at,:addition_approved_at)");
        $stat->execute(array(
            'venue_id'=>$venue->getId(),
            'media_id'=>$media->getId(),
            'added_by_user_account_id'=>$user->getId(),
            'added_at'=>  $this->app['timesource']->getFormattedForDataBase(),
            'addition_approved_at'=>  $this->app['timesource']->getFormattedForDataBase(),
        ));

        $this->app['messagequeproducerhelper']->send('org.openacalendar', 'MediaInVenueSaved', array('media_id'=>$media->getId(),'venue_id'=>$venue->getId()));
    }


    public function remove(MediaModel $media, VenueModel $venue, UserAccountModel $user)
    {
        $stat = $this->app['db']->prepare("UPDATE media_in_venue SET removed_by_user_account_id=:removed_by_user_account_id,".
                " removed_at=:removed_at, removal_approved_at=:removal_approved_at WHERE ".
                " venue_id=:venue_id AND media_id=:media_id AND removed_at IS NULL ");
        $stat->execute(array(
                'venue_id'=>$venue->getId(),
                'media_id'=>$media->getId(),
                'removed_at'=>  $this->app['timesource']->getFormattedForDataBase(),
                'removal_approved_at'=>  $this->app['timesource']->getFormattedForDataBase(),
                'removed_by_user_account_id'=>$user->getId(),
            ));

        $this->app['messagequeproducerhelper']->send('org.openacalendar', 'MediaInVenueSaved', array('media_id'=>$media->getId(),'venue_id'=>$venue->getId()));
    }
}
