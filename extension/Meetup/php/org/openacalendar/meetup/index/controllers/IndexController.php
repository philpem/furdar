<?php

namespace org\openacalendar\meetup\index\controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class IndexController
{
    public function index(Request $request, Application $app)
    {


        $state = $_GET['state'];
        if ($state != $app['websession']->get('meetup-oauth-signin-state')) {
            return "BAD STATE";
        }


        $extension = $app['extensions']->getExtensionById('org.openacalendar.meetup');
        $url = 'https://secure.meetup.com/oauth2/access';
        $data_to_send = [
            'client_id' => $app['appconfig']->getValue($extension->getAppConfigurationDefinition('oauth_key')),
            'client_secret' => $app['appconfig']->getValue($extension->getAppConfigurationDefinition('oauth_secret')) ,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $app['config']->getWebIndexDomainSecure().'/meetupdotcomoauthcallback',
            'code' => $_GET['code'],
        ];

        $guzzle = new Client(array('defaults' => array('headers' => array(  'User-Agent'=> 'Calendar Software') )));
        try {
            $response = $guzzle->request('POST', $url, ['form_params' => $data_to_send ]);
            if ($response->getStatusCode() != 200) {
                return 'NON 200 RESPONSE FROM MEETUP';
            }
        } catch (TransferException $e) {
            return 'EXCEPTION CALLING MEETUP';
        }

        $data = json_decode($response->getBody(), true);

        $app['appconfig']->setValue($extension->getAppConfigurationDefinition('access_token'), $data['access_token']);
        $app['appconfig']->setValue($extension->getAppConfigurationDefinition('refresh_token'), $data['refresh_token']);

        return "DONE!";

    }
}
