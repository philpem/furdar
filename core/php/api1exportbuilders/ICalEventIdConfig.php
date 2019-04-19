<?php
namespace api1exportbuilders;

/**
 *
 * This was at one stage used for a Google Calendar import hack.
 * (See https://github.com/OpenACalendar/OpenACalendar-Web-Core/issues/176 )
 * However, now it's not needed and we may remove it.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ICalEventIdConfig
{
    protected $isSlug = false;

    protected $isSlugStartEnd = false;

    public function __construct($option = null, $server = array())
    {
        if (strtolower(trim($option)) == 'slug') {
            $this->isSlug = true;
            return;
        }

        if (strtolower(trim($option)) == 'slugstartend') {
            $this->isSlugStartEnd = true;
            return;
        }

        // Nothing selected. The Default.
        $this->isSlug = true;
    }

    /**
     * @return boolean
     */
    public function isSlug()
    {
        return $this->isSlug;
    }

    /**
     * @return boolean
     */
    public function isSlugStartEnd()
    {
        return $this->isSlugStartEnd;
    }
}
