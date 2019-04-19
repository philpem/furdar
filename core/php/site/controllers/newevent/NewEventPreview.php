<?php

namespace site\controllers\newevent;

use models\EventModel;
use repositories\CountryRepository;
use site\forms\EventNewWhatDetailsForm;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewEventPreview extends BaseNewEvent
{
    public function getTitle()
    {
        return 'Preview';
    }

    public function getStepID()
    {
        return 'preview';
    }


    public function processIsAllInformationGathered()
    {
    }

    public function onThisStepSetUpPageView()
    {
        $out = array();

        if ($this->draftEvent->getDetailsValue('event.country_id')) {
            $countryRepository = new CountryRepository($this->application);
            $out['country'] = $countryRepository->loadById($this->draftEvent->getDetailsValue('event.country_id'));
        }

        return $out;
    }
}
