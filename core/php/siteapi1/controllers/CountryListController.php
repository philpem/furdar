<?php

namespace siteapi1\controllers;

use repositories\builders\CountryRepositoryBuilder;
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
class CountryListController
{
    public function json(Request $request, Application $app)
    {
        $countryRepoBuilder = new CountryRepositoryBuilder($app);
        $countryRepoBuilder->setSiteIn($app['currentSite']);

        if (isset($_GET['titleSearch']) && trim($_GET['titleSearch'])) {
            $countryRepoBuilder->setTitleSearch($_GET['titleSearch']);
        }

        if (isset($_GET['limit']) && intval($_GET['limit']) > 0) {
            $countryRepoBuilder->setLimit(intval($_GET['limit']));
        } else {
            $countryRepoBuilder->setLimit($app['config']->api1CountryListLimit);
        }

        $out = array();

        foreach ($countryRepoBuilder->fetchAll() as $country) {
            $out[] = array(
                'twoCharCode'=>$country->getTwoCharCode(),
                'title'=>$country->getTitle(),
            );
        }

        $response = new Response(json_encode(array('data'=>$out)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
