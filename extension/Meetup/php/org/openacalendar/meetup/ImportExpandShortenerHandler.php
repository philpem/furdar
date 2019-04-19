<?php

namespace org\openacalendar\meetup;

use import\ImportHandlerBase;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportExpandShortenerHandler extends ImportHandlerBase
{
    public function getSortOrder()
    {
        return 1000;
    }

    public function isStopAfterHandling()
    {
        return false;
    }

    protected $newFeedURL;

    public function canHandle()
    {
        global $CONFIG;
        $urlBits = parse_url($this->importRun->getRealURL());

        if (in_array(strtolower($urlBits['host']), array('meetu.ps'))) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->importRun->getRealUrl());
            curl_setopt($ch, CURLOPT_USERAGENT, 'Open Tech Calendar, install '.$CONFIG->webIndexDomain);
            curl_exec($ch);
            $response = curl_getinfo($ch);
            curl_close($ch);
            if ($response['http_code'] == 301 || $response['http_code'] == 302) {
                $this->newFeedURL = $response['redirect_url'];
                return true;
            }
        }

        return false;
    }

    public function getNewFeedURL()
    {
        return $this->newFeedURL;
    }

    public function handle()
    {
        if ($this->newFeedURL) {
            $this->importRun->setRealUrl($this->newFeedURL);
        }
    }
}
