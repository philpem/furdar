<?php

namespace siteapi1\controllers;

use Silex\Application;
use site\forms\EventNewForm;
use Symfony\Component\HttpFoundation\Request;
use repositories\VenueRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueExtraController
{
    protected $parameters = array();

    protected function build($slug, Request $request, Application $app)
    {
        $this->parameters = array();

        if (strpos($slug, "-") > 0) {
            $slugBits = explode("-", $slug, 2);
            $slug     = $slugBits[0];
        }

        $vr                        = new VenueRepository($app);
        $this->parameters['venue'] = $vr->loadBySlug($app['currentSite'], $slug);
        if (! $this->parameters['venue']) {
            return false;
        }

        return true;
    }


    public function json($slug, Request $request, Application $app)
    {
        if (! $this->build($slug, $request, $app)) {
            $app->abort(404, "Venue does not exist.");
        }

        $data = array(
            'title'=>$this->parameters['venue']->getTitle(),
            'description'=>$this->parameters['venue']->getDescription(),
            'lat'=>$this->parameters['venue']->getLat(),
            'lat'=>$this->parameters['venue']->getLng(),
            'slug'=>$this->parameters['venue']->getSlug(),
            'slugForURL'=>$this->parameters['venue']->getSlugForURL(),
        );

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
