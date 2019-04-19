<?php

namespace org\openacalendar\curatedlists;

use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AddContentToEventShowPage extends \BaseAddContentToEventShowPage
{
    /** @var Application */
    protected $app;
    protected $parameters;

    public function __construct($parameters, Application $app)
    {
        $this->parameters = $parameters;
        $this->app = $app;

        $app['currentUserActions']->set(
            "org.openacalendar.curatedlists",
            "eventEditCuratedLists",
            $app['currentUserActions']->has("org.openacalendar", "curatedListGeneralEdit")
            && !$this->parameters['event']->getIsDeleted()
        );
        // not && !$this->parameters['event']->getIsCancelled() because if cancelled want to remove from lists
    }

    public function getParameters()
    {
        $parameters = array();

        $curatedListRepoBuilder = new CuratedListRepositoryBuilder($this->app);
        $curatedListRepoBuilder->setContainsEvent($this->parameters['event']);
        $curatedListRepoBuilder->setIncludeDeleted(false);
        $parameters['curatedLists'] = $curatedListRepoBuilder->fetchAll();

        return $parameters;
    }


    public function getTemplatesAtEnd()
    {
        return array('site/event/show.curatedlists.html.twig');
    }
}
