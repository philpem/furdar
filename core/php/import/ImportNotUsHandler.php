<?php

namespace import;

use models\ImportResultModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportNotUsHandler extends ImportHandlerBase
{
    public function getSortOrder()
    {
        return -1000000;
    }
    
    public function canHandle()
    {
        $data = parse_url($this->importRun->getRealUrl());
        $host = isset($data['host']) ? $data['host'] : '';
        
        
        $checks = array($this->getDomainMinusPort($this->app['config']->webIndexDomain),$this->getDomainMinusPort($this->app['config']->webSiteDomain));
        if ($this->app['config']->hasSSL) {
            $checks[] = $this->getDomainMinusPort($this->app['config']->webSiteDomain);
            $checks[] = $this->getDomainMinusPort($this->app['config']->webSiteDomainSSL);
        }
        foreach ($checks as $check) {
            if (strpos(strtolower($host), strtolower($check)) !== false) {
                //print "\n\n".$host." AND ".$check."\n\n";
                return true;
            }
        }
        
        return false;
    }
    
    public function getDomainMinusPort(string $in)
    {
        if (strpos($in, ":")) {
            $bits = explode(":", $in);
            return $bits[0];
        } else {
            return $in;
        }
    }
    
    public function handle()
    {
        $iurlr = new ImportResultModel();
        $iurlr->setIsSuccess(false);
        $iurlr->setMessage("You can't import from the same site!");
        return $iurlr;
    }
}
