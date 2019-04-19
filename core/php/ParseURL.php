<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ParseURL
{
    protected $url;
    
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    public function getCanonical()
    {
        $data = parse_url($this->url);
        
        $url = ($data['scheme'] ? $data['scheme'].":":'') . '//';
        if ((isset($data['username']) && $data['username']) || (isset($data['password']) && $data['password'])) {
            $url .= $data['username'] . ":" . $data['password'] . "@";
        }
        $url .= strtolower($data['host']).(isset($data['path'])?$data['path']:'/').'?'.(isset($data['query']) ? $data['query'] : '');
        
        return $url;
    }
}
