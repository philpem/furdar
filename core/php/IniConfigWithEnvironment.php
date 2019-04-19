<?php



/**
 *
 *
 * This loads ini files with variables in sections by Environments or a Common section for all Environments.
 *
 * eg
 *
 * [Common]
 * FromEmail=james@example.com
 * FromName=James Baster
 *
 * [EnvironmentTest]
 * To=james@example.com
 *
 * [EnvironmentReal]
 * To=emaillist@example.com
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IniConfigWithEnvironment
{
    protected $environment;
    protected $data;
    public function __construct($environment, $filename)
    {
        $this->environment = $environment;
        $this->data = parse_ini_file($filename, true);
    }
    public function hasValue($key)
    {
        if (isset($this->data['Environment'.$this->environment][$key]) && $this->data['Environment'.$this->environment][$key]) {
            return true;
        } else {
            return isset($this->data['Common'][$key]) && $this->data['Common'][$key];
        }
    }
    public function get($key, $default=null)
    {
        if (isset($this->data['Environment'.$this->environment][$key])) {
            return $this->data['Environment'.$this->environment][$key];
        }
        if (isset($this->data['Common'][$key])) {
            return $this->data['Common'][$key];
        }
        return $default;
    }
    public function getBoolean($key, $default="false")
    {
        $val = $this->get($key, $default);
        return in_array(strtolower(trim($val)), array("true","yes","1"));
    }
}
