<?php

namespace api1exportbuilders;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


trait TraitICal
{
    
    
    /**
     * Public for testing
     * @param type $key
     * @param type $value
     * @return type
     */
    public function getIcalLine(string $key, string $value)
    {
        // should be wrapping long lines and escaping new lines
        $value = str_replace("\\", "\\\\", $value);
        $value = str_replace("\r", "", str_replace("\n", '\\n', $value));
        $value = str_replace(";", "\\;", $value);
        $value = str_replace(",", "\\,", $value);
        $value = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value);
        // google calendar does not like a space after the ':'.
        $out = $key.":".$value;
        if (strlen($out) > 75) {
            $out = $key.":";
            # first Line;
            $charsToAdd = 75-strlen($out);
            $out .= substr($value, 0, $charsToAdd)."\r\n";
            $value = substr($value, $charsToAdd);
            # rest of the lines
            while ($value) {
                $out .= " ".substr($value, 0, 74)."\r\n";
                $value = substr($value, 74);
            }
            return $out;
        } else {
            return $out."\r\n";
        }
    }

    /** Special function as this needs a ; that is not escaped in it! */
    public function getIcalGeoLine(float $lat, float $lng)
    {
        $value = $lat. ";" . $lng;
        // google calendar does not like a space after the ':'.
        $out = "GEO:".$value;
        if (strlen($out) > 75) {
            $out = "GEO:";
            # first Line;
            $charsToAdd = 75-strlen($out);
            $out .= substr($value, 0, $charsToAdd)."\r\n";
            $value = substr($value, $charsToAdd);
            # rest of the lines
            while ($value) {
                $out .= " ".substr($value, 0, 74)."\r\n";
                $value = substr($value, 74);
            }
            return $out;
        } else {
            return $out."\r\n";
        }
    }
}
