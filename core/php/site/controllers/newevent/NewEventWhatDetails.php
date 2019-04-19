<?php

namespace site\controllers\newevent;

use models\EventModel;
use repositories\SiteFeatureRepository;
use site\forms\EventNewWhatDetailsForm;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewEventWhatDetails extends BaseNewEvent
{
    public function processIsAllInformationGathered()
    {
        if ($this->draftEvent->getDetailsValue('event.summary')) {
            $this->isAllInformationGathered = true;
        }
    }

    public function getTitle()
    {
        return 'What';
    }

    public function getStepID()
    {
        return 'what';
    }

    public function canJumpBackToHere()
    {
        return true;
    }

    protected $customFields;
    protected $form;

    public function onThisStepSetUpPage()
    {
        $this->customFields = array();
        foreach ($this->application['currentSite']->getCachedEventCustomFieldDefinitionsAsModels() as $customField) {
            if ($customField->getIsActive()) {
                $extension = $this->application['extensions']->getExtensionById($customField->getExtensionId());
                if ($extension) {
                    $fieldType = $extension->getEventCustomFieldByType($customField->getType());
                    if ($fieldType) {
                        $this->customFields[] = array('fieldType'=>$fieldType, 'customField'=>$customField);
                    }
                }
            }
        }

        $this->form = $this->application['form.factory']->create(EventNewWhatDetailsForm::class, null, array('app'=>$this->application,'newEventDraftModel'=>$this->draftEvent,'customFields'=>$this->customFields));

        return array();
    }

    public function onThisStepProcessPage()
    {
        if ('POST' == $this->request->getMethod()) {
            $this->form->handleRequest($this->request);
            if ($this->form->isValid()) {
                $this->draftEvent->setDetailsValue('event.summary', $this->form->get('summary')->getData());
                $this->draftEvent->setDetailsValue('event.description', $this->form->get('description')->getData());
                $this->draftEvent->setDetailsValue('event.url', $this->form->get('url')->getData());
                $this->draftEvent->setDetailsValue('event.ticket_url', $this->form->get('ticket_url')->getData());

                if ($this->form->has('is_physical')) {
                    $this->draftEvent->setDetailsValue('event.is_physical', $this->form->get('is_physical')->getData());
                }
                if ($this->form->has('is_virtual')) {
                    $this->draftEvent->setDetailsValue('event.is_virtual', $this->form->get('is_virtual')->getData());
                }

                foreach ($this->customFields as $customFieldData) {
                    $this->draftEvent->setDetailsValue('event.custom.' . $customFieldData['customField']->getKey(), $this->form->get('custom_'.$customFieldData['customField']->getKey())->getData());
                }

                $this->isAllInformationGathered = true;
                return true;
            }
        }

        return false;
    }

    /** return array of variables to add to paramaters */
    public function onThisStepSetUpPageView()
    {
        $customFieldsForForm = array();
        foreach ($this->customFields as $customFieldData) {
            $customFieldsForForm[] = $customFieldData['customField'];
        }
        return array(
            'form' => $this->form->createView(),
            'formCustomFields' => $customFieldsForForm,
        );
    }


    public function onThisStepGetViewName()
    {
        return 'site/eventnew/eventDraft.what.form.html.twig';
    }

    public function onThisStepGetViewJavascriptName()
    {
        return 'site/eventnew/eventDraft.what.form.javascript.html.twig';
    }


    public function stepDoneGetViewName()
    {
        return 'site/eventnew/eventDraft.what.preview.html.twig';
    }

    public function stepDoneGetMinimalViewName()
    {
        return 'site/eventnew/eventDraft.what.minimalpreview.html.twig';
    }

    public function addDataToEventBeforeSave(EventModel $eventModel)
    {
        $this->addDataToEventBeforeCheck($eventModel);
    }

    public function addDataToEventBeforeCheck(EventModel $eventModel)
    {
        $eventModel->setSummary($this->draftEvent->getDetailsValue('event.summary'));
        $eventModel->setDescription($this->draftEvent->getDetailsValue('event.description'));
        $eventModel->setUrl($this->draftEvent->getDetailsValue('event.url'));
        $eventModel->setTicketUrl($this->draftEvent->getDetailsValue('event.ticket_url'));

        foreach ($this->site->getCachedEventCustomFieldDefinitionsAsModels() as $customField) {
            if ($this->draftEvent->hasDetailsValue('event.custom.'. $customField->getKey())) {
                $eventModel->setCustomField($customField, $this->draftEvent->getDetailsValue('event.custom.'. $customField->getKey()));
            }
        }

        $siteFeatureRepo = new SiteFeatureRepository($this->application);
        $siteFeaturePhysicalEvents = $siteFeatureRepo->doesSiteHaveFeatureByExtensionAndId($this->site, 'org.openacalendar', 'PhysicalEvents');
        $siteFeatureVirtualEvents = $siteFeatureRepo->doesSiteHaveFeatureByExtensionAndId($this->site, 'org.openacalendar', 'VirtualEvents');


        if ($siteFeaturePhysicalEvents && $siteFeatureVirtualEvents) {
            $eventModel->setIsPhysical($this->draftEvent->getDetailsValue('event.is_physical'));
            $eventModel->setIsVirtual($this->draftEvent->getDetailsValue('event.is_virtual'));
        } elseif ($siteFeaturePhysicalEvents) {
            $eventModel->setIsPhysical(true);
            $eventModel->setIsVirtual(false);
        } elseif ($siteFeatureVirtualEvents) {
            $eventModel->setIsPhysical(false);
            $eventModel->setIsVirtual(true);
        }
    }
}
