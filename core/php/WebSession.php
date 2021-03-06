<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class WebSession
{
    public function __construct($app)
    {
        ini_set("session.cookie_httponly", "1");
        ini_set("session.cookie_domain", $app['config']->webCommonSessionDomain);
        ini_set("session.gc_maxlifetime", $app['config']->sessionLastsInSeconds);
        if ($app['config']->hasSSL && $app['config']->forceSSL) {
            ini_set("session.cookie_secure", true);
        } else {
            ini_set("session.cookie_secure", false);
        }
        // We must set this. If we don't, PHP "helpfully" adds big sodding "nocache" headers everywhere -
        //   and some pages we actually do want cached.
        ini_set("session.cache_limiter", "");
    }
    
    private function startSessionIfNeededForWriting()
    {
        if (!session_id()) {
            //debug_print_backtrace(); die();
            session_start();
        }
    }
    
    private function startSessionIfNeededForReading()
    {
        // Does user have a session? If not don't start one here, just leave it and let all read functions return blank
        if (!session_id() && isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID']) {
            //debug_print_backtrace(); die();
            session_start();
        }
    }
    
    public function has($key)
    {
        $this->startSessionIfNeededForReading();
        return isset($_SESSION) && isset($_SESSION[$key]) && $_SESSION[$key];
    }
    
    public function get($key)
    {
        $this->startSessionIfNeededForReading();
        return isset($_SESSION) && isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    
    public function getCSFRToken()
    {
        $this->startSessionIfNeededForReading();
        if (!isset($_SESSION['CSFRToken'])) {
            $this->startSessionIfNeededForWriting();
            $_SESSION['CSFRToken'] = createKey(2, 200);
        }
        return $_SESSION['CSFRToken'];
    }
    
    public function set($key, $value)
    {
        $this->startSessionIfNeededForWriting();
        $_SESSION[$key] = $value;
    }
    
    public function hasArray($key)
    {
        $this->startSessionIfNeededForReading();
        return isset($_SESSION) && isset($_SESSION[$key]) && is_array($_SESSION[$key]);
    }
    
    /**
     * @param type $key
     * @return type Always returns an array. Returns a  blank array if in fact that key doesn't exist.
     */
    public function getArray($key)
    {
        $this->startSessionIfNeededForReading();
        return isset($_SESSION) && isset($_SESSION[$key]) && is_array($_SESSION[$key]) ? $_SESSION[$key] : array();
    }
    
    public function inArray($key, $needle)
    {
        $this->startSessionIfNeededForReading();
        return in_array($needle, $this->getArray($key));
    }
    
    public function setArray($key, $value)
    {
        $this->startSessionIfNeededForWriting();
        $_SESSION[$key] = $value;
    }
    
    public function appendArray($key, $value)
    {
        $this->startSessionIfNeededForWriting();
        if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
            $_SESSION[$key] = array();
        }
        $_SESSION[$key][] = $value;
    }

    public function removeValueFromArray($key, $value)
    {
        $this->startSessionIfNeededForWriting();
        if (isset($_SESSION[$key]) && is_array($_SESSION[$key])) {
            if (($keyInArray = array_search($value, $_SESSION[$key])) !== false) {
                unset($_SESSION[$key][$keyInArray]);
            }
        }
    }
}
