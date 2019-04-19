<?php

namespace org\openacalendar\displayboard;

use appconfiguration\AppConfigurationDefinition;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionDisplayBoard extends \BaseExtension
{
    public function getId()
    {
        return 'org.openacalendar.displayboard';
    }
    
    public function getTitle()
    {
        return "Displayboard";
    }
    
    public function getDescription()
    {
        return "Displayboard";
    }
}
