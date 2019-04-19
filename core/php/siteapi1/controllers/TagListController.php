<?php

namespace siteapi1\controllers;

use repositories\builders\TagRepositoryBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagListController
{
    public function json(Request $request, Application $app)
    {
        $tagRepoBuilder = new TagRepositoryBuilder($app);
        $tagRepoBuilder->setSite($app['currentSite']);
        
        if (isset($_GET['titleSearch']) && trim($_GET['titleSearch'])) {
            $tagRepoBuilder->setTitleSearch($_GET['titleSearch']);
        }
        
        if (isset($_GET['includeDeleted'])) {
            if (in_array(strtolower($_GET['includeDeleted']), array('yes','on','1'))) {
                $tagRepoBuilder->setIncludeDeleted(true);
            } elseif (in_array(strtolower($_GET['includeDeleted']), array('no','off','0'))) {
                $tagRepoBuilder->setIncludeDeleted(false);
            }
        }

        if (isset($_GET['limit']) && intval($_GET['limit']) > 0) {
            $tagRepoBuilder->setLimit(intval($_GET['limit']));
        } else {
            $tagRepoBuilder->setLimit($app['config']->api1TagListLimit);
        }
        
        $out = array();
        
        foreach ($tagRepoBuilder->fetchAll() as $tag) {
            $out[] = array(
                    'slug'=>$tag->getSlug(),
                    'title'=>$tag->getTitle(),
                );
        }
        
        $response = new Response(json_encode(array('data'=>$out)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
