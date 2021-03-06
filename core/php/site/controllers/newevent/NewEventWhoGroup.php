<?php

namespace site\controllers\newevent;

use models\EventModel;
use models\GroupModel;
use repositories\builders\GroupRepositoryBuilder;
use repositories\GroupRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewEventWhoGroup extends BaseNewEvent
{
    public function processIsAllInformationGathered()
    {
        if ($this->draftEvent->getDetailsValue('group.none') || $this->draftEvent->getDetailsValue('group.id') || $this->draftEvent->getDetailsValue('group.new') == 1 ||  $this->draftEvent->getDetailsValue('group.title')) {
            $this->isAllInformationGathered = true;
        }
    }

    public function getTitle()
    {
        return 'Who';
    }

    public function getStepID()
    {
        return 'who';
    }

    public function canJumpBackToHere()
    {
        return true;
    }

    public function onThisStepGetViewName()
    {
        return 'site/eventnew/eventDraft.who.html.twig';
    }


    public function onThisStepGetViewJavascriptName()
    {
        return 'site/eventnew/eventDraft.who.javascript.html.twig';
    }
    public function onThisStepProcessPage()
    {
        if ($this->request->request->get('action') == 'nogroup') {
            $this->draftEvent->setDetailsValue('group.none', true);
            $this->draftEvent->setDetailsValue('group.new', false);
            $this->draftEvent->setDetailsValue('group.existing', false);
            $this->isAllInformationGathered = true;
            return true;
        }

        if ($this->request->request->get('action') == 'group') {
            $this->draftEvent->setDetailsValue('group.none', false);
            $this->draftEvent->setDetailsValue('group.new', false);
            $this->draftEvent->setDetailsValue('group.existing', true);
            return true;
        }

        if ($this->request->request->get('action') == 'selectgroup') {
            $gr = new GroupRepository($this->application);
            $group = $gr->loadBySlug($this->site, $this->request->request->get('group'));
            if ($group) {
                $this->draftEvent->setDetailsValue('group.new', false);
                $this->draftEvent->setDetailsValue('group.id', $group->getId());
                $this->draftEvent->setDetailsValue('group.title', $group->getTitle());
                $this->isAllInformationGathered = true;
                return true;
            }
        }

        if ($this->request->request->get('action') == 'selectnewgroup' && $this->request->request->get('newgrouptitle')) {
            $this->draftEvent->setDetailsValue('group.new', true);
            $this->draftEvent->setDetailsValue('group.title', $this->request->request->get('newgrouptitle'));
            $this->isAllInformationGathered = true;
            return true;
        }
    }



    public function onThisStepSetUpPageView()
    {
        if ($this->draftEvent->getDetailsValue('group.existing')) {
            $out = array(
                'groupExisting' => true,
                'groupSearchText' => $this->request->request->get('groupsearch')
            );

            if ($this->request->request->get('action') == 'groupsearch') {
                $grb = new GroupRepositoryBuilder($this->application);
                $grb->setSite($this->site);
                $grb->setIncludeDeleted(false);
                $grb->setFreeTextsearch($this->request->request->get('groupsearch'));
                $grb->setLimit(100);
                $out['groups'] = $grb->fetchAll();
            }

            return $out;
        } else {
            $out = array(
                'groupExisting' => false,
                'incomingGroup' => null,
            );

            if ($this->draftEvent->hasDetailsValue('incoming.group.id')) {
                $gr = new GroupRepository($this->application);
                $group = $gr->loadById($this->draftEvent->getDetailsValue('incoming.group.id'));
                if ($group) {
                    $out['incomingGroup'] = $group;
                }
            }

            return $out;
        }
    }

    public function stepDoneGetViewName()
    {
        if ($this->draftEvent->getDetailsValue('group.title')) {
            return 'site/eventnew/eventDraft.who.preview.html.twig';
        }
    }

    public function stepDoneGetMinimalViewName()
    {
        if ($this->draftEvent->getDetailsValue('group.title')) {
            return 'site/eventnew/eventDraft.who.minimalpreview.html.twig';
        }
    }

    public function addDataToEventBeforeSave(EventModel $eventModel)
    {
        if ($this->draftEvent->hasDetailsValue('group.new') && $this->draftEvent->getDetailsValue('group.new')) {
            $group = new GroupModel();
            $group->setTitle($this->draftEvent->getDetailsValue('group.title'));

            $groupRepo = new GroupRepository($this->application);
            $groupRepo->create($group, $this->site, $this->application['currentUser']);

            $eventModel->setGroupId($group->getId());
        } elseif ($this->draftEvent->getDetailsValue('group.id')) {
            $eventModel->setGroupId($this->draftEvent->getDetailsValue('group.id'));
        }
    }

    public function addDataToEventBeforeCheck(EventModel $eventModel)
    {
        if ($this->draftEvent->hasDetailsValue('group.new') && $this->draftEvent->getDetailsValue('group.new')) {
            // Nothing to do here!
        } elseif ($this->draftEvent->getDetailsValue('group.id')) {
            $eventModel->setGroupId($this->draftEvent->getDetailsValue('group.id'));
        }
    }
}
