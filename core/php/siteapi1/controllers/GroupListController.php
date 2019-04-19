<?php

namespace siteapi1\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use repositories\builders\GroupRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupListController
{
    public function json(Request $request, Application $app)
    {
        $groupRepoBuilder = new GroupRepositoryBuilder($app);
        $groupRepoBuilder->setSite($app['currentSite']);

        if (isset($_GET['titleSearch']) && trim($_GET['titleSearch'])) {
            $groupRepoBuilder->setTitleSearch($_GET['titleSearch']);
        }

        if (isset($_GET['search']) && trim($_GET['search'])) {
            $groupRepoBuilder->setFreeTextsearch($_GET['search']);
        }
        
        if (isset($_GET['includeDeleted'])) {
            if (in_array(strtolower($_GET['includeDeleted']), array('yes','on','1'))) {
                $groupRepoBuilder->setIncludeDeleted(true);
            } elseif (in_array(strtolower($_GET['includeDeleted']), array('no','off','0'))) {
                $groupRepoBuilder->setIncludeDeleted(false);
            }
        }

        if (isset($_GET['limit']) && intval($_GET['limit']) > 0) {
            $groupRepoBuilder->setLimit(intval($_GET['limit']));
        } else {
            $groupRepoBuilder->setLimit($app['config']->api1GroupListLimit);
        }
        
        $out = array();

        foreach ($groupRepoBuilder->fetchAll() as $group) {
            $out[] = array(
                'slug'=>$group->getSlug(),
                'title'=>$group->getTitle(),
                'description'=>$group->getDescription(),
            );
        }
        
        $response = new Response(json_encode(array('data'=>$out)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
