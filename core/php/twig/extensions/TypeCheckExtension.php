<?php

namespace twig\extensions;

use Silex\Application;
use models\EventHistoryModel;
use models\GroupHistoryModel;
use models\VenueHistoryModel;
use models\AreaHistoryModel;
use models\TagHistoryModel;
use models\ImportHistoryModel;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TypeCheckExtension extends \Twig_Extension
{
    protected $app;

    public function __construct(Application $app = null)
    {
        $this->app = $app;
    }

    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('eventhistory', function ($item) {
                return $item instanceof EventHistoryModel;
            }),
            new \Twig_SimpleTest('grouphistory', function ($item) {
                return $item instanceof GroupHistoryModel;
            }),
            new \Twig_SimpleTest('venuehistory', function ($item) {
                return $item instanceof VenueHistoryModel;
            }),
            new \Twig_SimpleTest('areahistory', function ($item) {
                return $item instanceof AreaHistoryModel;
            }),
            new \Twig_SimpleTest('taghistory', function ($item) {
                return $item instanceof TagHistoryModel;
            }),
            new \Twig_SimpleTest('importurlhistory', function ($item) {
                return $item instanceof ImportHistoryModel;
            }),
        );
    }

    public function getName()
    {
        return 'jarofgreen_wikicalendar_typecheck';
    }
}
