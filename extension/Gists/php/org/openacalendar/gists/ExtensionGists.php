<?php

namespace org\openacalendar\gists;

use org\openacalendar\gists\userpermissions\GistsUserPermission;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionGists extends \BaseExtension
{
    public function getId()
    {
        return 'org.openacalendar.gists';
    }

    public function getTitle()
    {
        return "Gists";
    }

    public function getDescription()
    {
        return "Gists";
    }

    public function getUserPermissions()
    {
        return array('GISTS');
    }

    public function getUserPermission($key)
    {
        if ($key == 'GISTS') {
            return new GistsUserPermission();
        }
    }
}
