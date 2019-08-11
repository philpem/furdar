<?php

namespace org\openacalendar\meetup;

use appconfiguration\AppConfigurationDefinition;
use GuzzleHttp\Client;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionMeetup extends \BaseExtension
{
    public function getId()
    {
        return 'org.openacalendar.meetup';
    }
    
    public function getTitle()
    {
        return "Meetup Integration";
    }
    
    public function getDescription()
    {
        return "Meetup Integration";
    }
    
    public function getAppConfigurationDefinitions()
    {
        return array(
            new AppConfigurationDefinition($this->getId(), 'oauth_key', 'password', true),
            new AppConfigurationDefinition($this->getId(), 'oauth_secret', 'password', true),
            new AppConfigurationDefinition($this->getId(), 'access_token', 'password', false),
            new AppConfigurationDefinition($this->getId(), 'refresh_token', 'password', false),
        );
    }
    
    public function getImportHandlers()
    {
        return array(
            new ImportExpandShortenerHandler($this->app),
            new ImportMeetupHandler($this->app),
        );
    }

    public function getSysAdminLinks()
    {
        return array(
            new \SysAdminLink("Setup Meetup Access", '/sysadmin/meetupuser')
        );
    }

    public function getImportURLRecommendations(\import\ImportURLRecommendationDataToCheck $dataToCheck)
    {
        $importURLRecommendation = new ImportURLRecommendation($dataToCheck->getUrl());
        if ($importURLRecommendation->hasNewURL()) {
            return array($importURLRecommendation);
        } else {
            return array();
        }
    }

    public function callMeetupAPI($guzzle, $path) {

        try {
            $response = $guzzle->request('GET', 'https://api.meetup.com/' . $path, [
                'headers' => [
                    'User-Agent'=> 'Prototype Software',
                    'Authorization'=> 'Bearer '. $this->app['appconfig']->getValue($this->getAppConfigurationDefinition('access_token')),
                ]
            ]);

        } catch (\Exception $exception) {
            throw $exception;
        }


        ########### TODO https://www.meetup.com/meetup_api/auth/#oauth2-refresh

        return $response;
    }




}
