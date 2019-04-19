<?php

namespace site\controllers\newevent;

use models\EventModel;
use models\NewEventDraftModel;
use models\SiteModel;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseNewEvent
{


    //public static $TYPE_WHO = 1;
    //public static $TYPE_WHAT = 2;
    //public static $TYPE_WHEN = 3;
    //public static $TYPE_WHERE = 4;

    /** @var NewEventDraftModel */
    protected $draftEvent;

    /** @var Silex/ */
    protected $application;

    /** @var  Request */
    protected $request;


    /** @var  SiteModel */
    protected $site;

    public function __construct(NewEventDraftModel $draftEvent, Application $application, Request $request)
    {
        $this->draftEvent = $draftEvent;
        $this->application = $application;
        $this->site = $application['currentSite'];
        $this->request = $request;
    }

    protected $isAllInformationGathered = false;

    /**
     * @return boolean
     */
    public function getIsAllInformationGathered()
    {
        return $this->isAllInformationGathered;
    }

    public function canJumpBackToHere()
    {
        return false;
    }

    abstract public function getTitle();

    abstract public function getStepID();

    /**
     * Do we have all the info we need against this step?
     * Work it out and store in $this->isAllInformationGathered TRUE if yes FALSE if no
     */
    abstract public function processIsAllInformationGathered();


    /** return array of variables to add to paramaters */
    public function onThisStepSetUpPage()
    {
        return array();
    }

    /** return boolean TRUE if processed, and want to save and reload. FALSE if not processed */
    public function onThisStepProcessPage()
    {
        return false;
    }

    /** return array of variables to add to paramaters */
    public function onThisStepSetUpPageView()
    {
        return array();
    }

    public function onThisStepAddAJAXCallData()
    {
        return array();
    }

    public function stepDoneGetViewName()
    {
        return '';
    }

    public function stepDoneGetMinimalViewName()
    {
        return '';
    }

    public function onThisStepGetViewJavascriptName()
    {
        return '';
    }

    public function onThisStepGetViewName()
    {
        return '';
    }

    public function addDataToEventBeforeSave(EventModel $eventModel)
    {
    }

    public function addDataToEventBeforeCheck(EventModel $eventModel)
    {
    }
}
