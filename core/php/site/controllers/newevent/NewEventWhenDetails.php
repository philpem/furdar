<?php

namespace site\controllers\newevent;

use JMBTechnologyLimited\ParseDateTimeRangeString\ParseDateTimeRangeString;
use models\EventModel;
use repositories\builders\CountryRepositoryBuilder;
use repositories\CountryInSiteRepository;
use repositories\CountryRepository;
use Silex\Application;
use site\forms\EventNewWhenDetailsForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewEventWhenDetails extends BaseNewEvent
{
    protected $MODE_FREE = 1;
    protected $MODE_DETAILS = 2;

    protected function getCurrentMode()
    {
        if ($this->draftEvent->getDetailsValue('event.start_end_freetext.done') || $this->draftEvent->getDetailsValue('incoming.event.start_at')) {
            return $this->MODE_DETAILS;
        } else {
            return $this->MODE_FREE;
        }
    }

    public function processIsAllInformationGathered()
    {
        if ($this->draftEvent->getDetailsValue('event.start_at')) {
            $this->isAllInformationGathered = true;
        }
    }

    public function getTitle()
    {
        return 'When';
    }

    public function getStepID()
    {
        return 'when';
    }

    public function canJumpBackToHere()
    {
        return true;
    }

    protected $defaultCountry;
    protected $form;

    public function onThisStepSetUpPage()
    {
        if ($this->getCurrentMode() == $this->MODE_FREE) {
            // nothing to do
        } else {
            // TODO use $request NOT POST
            $timezone = isset($_POST['event_new_when_details_form']) && isset($_POST['event_new_when_details_form']['timezone']) ? $_POST['event_new_when_details_form']['timezone'] : $this->application['currentTimeZone'];

            $this->defaultCountry = $this->getDefaultCountry($this->application);

            $this->form = $this->application['form.factory']->create(EventNewWhenDetailsForm::class, null, array(
                'timeZoneName'=>$timezone,
                'countryModel'=>$this->defaultCountry,
                'newEventDraftModel'=>$this->draftEvent,
                'app'=> $this->application
            ));
        }
        return array();
    }


    /**
     * @return \models\CountryModel
     */
    protected function getDefaultCountry(Application $app)
    {

        // Option 1 - Was it set as incoming?
        if ($this->draftEvent->getDetailsValue('incoming.event.country_id')) {
            $cr = new CountryRepository($app);
            $country = $cr->loadById($this->draftEvent->getDetailsValue('incoming.event.country_id'));
            if ($country) {
                $cisr = new CountryInSiteRepository($app);
                if ($cisr->isCountryInSite($country, $app['currentSite'])) {
                    return $country;
                }
            }
        }

        // Option 2 - work it out from Timezone?
        $crb = new CountryRepositoryBuilder($app);
        $crb->setSiteIn($app['currentSite']);
        foreach ($crb->fetchAll() as $country) {
            if (in_array($app['currentTimeZone'], $country->getTimezonesAsList())) {
                return $country;
            }
        }

        // This should never happen????
        return null;
    }

    protected $currentStart;
    protected $currentEnd;
    protected $currentTimeZone;

    public function onThisStepProcessPage()
    {
        if ($this->getCurrentMode() == $this->MODE_FREE) {
            if ($this->request->request->get('action') == 'startAndEndFreeText') {
                $this->draftEvent->setDetailsValue('event.start_end_freetext.start', \TimeSource::getDateTime());
                $this->draftEvent->setDetailsValue('event.start_end_freetext.end', \TimeSource::getDateTime());

                if ($this->request->request->get('startAndEndFreeText')) {
                    $parse = new ParseDateTimeRangeString(\TimeSource::getDateTime(), $this->application['currentTimeZone']);
                    $parseResult = $parse->parse($this->request->request->get('startAndEndFreeText'));

                    if ($parseResult->getStart()) {
                        $this->draftEvent->setDetailsValue('event.start_end_freetext.start', $parseResult->getStart());
                        // If no end is returned, just set start as sensible default
                        $this->draftEvent->setDetailsValue('event.start_end_freetext.end', $parseResult->getEnd() ? $parseResult->getEnd() : $parseResult->getStart());
                    }
                }

                $this->draftEvent->setDetailsValue('event.start_end_freetext.text', $this->request->request->get('startAndEndFreeText'));
                $this->draftEvent->setDetailsValue('event.start_end_freetext.done', 'yes');

                return true;
            }
        } else {
            if ('POST' == $this->request->getMethod()) {
                $this->form->handleRequest($this->request);

                // Store these on object for JS
                $this->currentStart = $this->form->get('start_at')->getData();
                $this->currentEnd = $this->form->get('end_at')->getData();
                $this->currentTimeZone = $this->form->get('timezone')->getData();

                if ($this->form->isValid()) {
                    $this->draftEvent->setDetailsValue('event.start_at', $this->form->get('start_at')->getData());
                    $this->draftEvent->setDetailsValue('event.end_at', $this->form->get('end_at')->getData());
                    $this->draftEvent->setDetailsValue('event.country_id', $this->form->get('country_id')->getData());
                    $this->draftEvent->setDetailsValue('event.timezone', $this->form->get('timezone')->getData());

                    $this->isAllInformationGathered = true;
                    return true;
                }
            }
        }
        return false;
    }

    public function onThisStepSetUpPageView()
    {
        if ($this->getCurrentMode() == $this->MODE_FREE) {
            return array();
        } else {
            return array(
                'form' => $this->form->createView(),
                'defaultCountry' => $this->defaultCountry,
            );
        }
    }

    public function onThisStepGetViewName()
    {
        if ($this->getCurrentMode() == $this->MODE_FREE) {
            return 'site/eventnew/eventDraft.when.freetext.html.twig';
        } else {
            return 'site/eventnew/eventDraft.when.form.html.twig';
        }
    }

    public function onThisStepGetViewJavascriptName()
    {
        if ($this->getCurrentMode() == $this->MODE_FREE) {
            return '';
        } else {
            return 'site/eventnew/eventDraft.when.form.javascript.html.twig';
        }
    }

    public function onThisStepAddAJAXCallData()
    {
        $data = array('readableStartEndRange'=>'');

        if ($this->getCurrentMode() == $this->MODE_FREE) {
            // nothing to do
        } else {
            if ($this->currentStart && $this->currentEnd) {
                if ($this->currentStart->getTimestamp() > $this->currentEnd->getTimestamp()) {
                    $data['readableStartEndRange'] = "(The start is after the end)";
                } else {
                    $data['readableStartEndRange'] = $this->application['twig']->render('site/eventnew/startendrange.html.twig', array(
                        'start' => $this->currentStart,
                        'end' => $this->currentEnd,
                        'timezone' => $this->currentTimeZone,
                    ));
                }
            }
        }

        return $data;
    }

    public function stepDoneGetViewName()
    {
        return 'site/eventnew/eventDraft.when.preview.html.twig';
    }


    public function stepDoneGetMinimalViewName()
    {
        return 'site/eventnew/eventDraft.when.minimalpreview.html.twig';
    }


    public function addDataToEventBeforeSave(EventModel $eventModel)
    {
        $this->addDataToEventBeforeCheck($eventModel);
    }

    public function addDataToEventBeforeCheck(EventModel $eventModel)
    {
        if ($this->draftEvent->hasDetailsValue('event.country_id')) {
            $eventModel->setCountryId($this->draftEvent->getDetailsValue('event.country_id'));
        }
        if ($this->draftEvent->hasDetailsValue('event.timezone')) {
            $eventModel->setTimezone($this->draftEvent->getDetailsValue('event.timezone'));
        }
        if ($this->draftEvent->hasDetailsValue('event.start_at')) {
            $eventModel->setStartAt($this->draftEvent->getDetailsValueAsDateTime('event.start_at'));
        }
        if ($this->draftEvent->hasDetailsValue('event.end_at')) {
            $eventModel->setEndAt($this->draftEvent->getDetailsValueAsDateTime('event.end_at'));
        }
    }
}
