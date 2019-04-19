<?php

namespace pingback;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ParsePingBack
{


    /** @return PingBack */
    public static function parseFromData($data)
    {
        $sourceURL = null;
        $targetURL = null;

        $doc = new \DOMDocument();
        if (!$doc->loadXML($data)) {
            // TODO
            return;
        }


        $x = $doc->getElementsByTagName("param");
        $sourceURL = $x->item(0)->getElementsByTagName("value")->item(0)->getElementsByTagName("string")->item(0)->textContent;
        $targetURL = $x->item(1)->getElementsByTagName("value")->item(0)->getElementsByTagName("string")->item(0)->textContent;

        return new PingBack($sourceURL, $targetURL);
    }
}
