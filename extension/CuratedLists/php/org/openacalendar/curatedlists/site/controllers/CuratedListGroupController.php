<?php

namespace org\openacalendar\curatedlists\site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use org\openacalendar\curatedlists\models\CuratedListModel;
use org\openacalendar\curatedlists\repositories\CuratedListRepository;
use org\openacalendar\curatedlists\site\forms\CuratedListEditForm;
use repositories\UserAccountRepository;
use repositories\GroupRepository;
use repositories\builders\UserAccountRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListGroupController
{
    protected $parameters = array();
    
    protected function build($slug, $gslug, Request $request, Application $app)
    {
        $this->parameters = array();

        $curatedlistRepository = new CuratedListRepository();
        $this->parameters['curatedlist'] =  $curatedlistRepository->loadBySlug($app['currentSite'], $slug);
        if (!$this->parameters['curatedlist']) {
            return false;
        }
        
        $groupRepository = new GroupRepository($app);
        $this->parameters['group'] =  $groupRepository->loadBySlug($app['currentSite'], $gslug);
        if (!$this->parameters['group']) {
            return false;
        }
        
        $this->parameters['currentUserCanEditCuratedList'] = $this->parameters['curatedlist']->canUserEdit($app['currentUser']);
        
        return true;
    }
    
    public function remove($slug, $gslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $gslug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }
        
        if ($this->parameters['currentUserCanEditCuratedList'] && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $curatedlistRepository = new CuratedListRepository();
            $curatedlistRepository->removeGroupFromCuratedList($this->parameters['group'], $this->parameters['curatedlist'], $app['currentUser']);
        }

        if ($request->request->get('returnTo', 'group') == 'group') {
            return $app->redirect("/group/".$this->parameters['group']->getSlugForURL());
        } elseif ($request->request->get('returnTo', 'group') == 'curatedlist') {
            return $app->redirect("/curatedlist/".$this->parameters['curatedlist']->getSlug());
        } elseif ($request->request->get('returnTo', 'group') == 'curatedlistgroups') {
            return $app->redirect("/curatedlist/".$this->parameters['curatedlist']->getSlug().'/groups');
        }
    }
    
    public function add($slug, $gslug, Request $request, Application $app)
    {
        if (!$this->build($slug, $gslug, $request, $app)) {
            $app->abort(404, "curatedlist does not exist.");
        }
        
        if ($this->parameters['currentUserCanEditCuratedList'] && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $curatedlistRepository = new CuratedListRepository();
            $curatedlistRepository->addGrouptoCuratedList($this->parameters['group'], $this->parameters['curatedlist'], $app['currentUser']);
        }
        
        if ($request->request->get('returnTo', 'group') == 'group') {
            return $app->redirect("/group/".$this->parameters['group']->getSlugForURL());
        } elseif ($request->request->get('returnTo', 'group') == 'curatedlist') {
            return $app->redirect("/curatedlist/".$this->parameters['curatedlist']->getSlug());
        }
    }
}
