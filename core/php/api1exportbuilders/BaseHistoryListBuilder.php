<?php
namespace api1exportbuilders;

use InterfaceHistoryModel;
use repositories\builders\HistoryRepositoryBuilder;
use models\SiteModel;
use models\EventHistoryModel;
use models\GroupHistoryModel;
use models\VenueHistoryModel;
use models\AreaHistoryModel;
use models\TagHistoryModel;
use models\ImportHistoryModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseHistoryListBuilder extends BaseBuilder
{
    
    
    /** @var HistoryRepositoryBuilder **/
    protected $historyRepositoryBuilder;

    protected $histories = array();


    public function __construct(Application $app, SiteModel $site = null, string $timeZone  = null, string $title = null)
    {
        parent::__construct($app, $site, $timeZone, $title);
        $this->historyRepositoryBuilder = new HistoryRepositoryBuilder($this->app);
        $this->historyRepositoryBuilder->getHistoryRepositoryBuilderConfig()->setLimit(100);
        if ($site) {
            $this->historyRepositoryBuilder->setSite($site);
        }
    }
    
    
    abstract public function addHistory(InterfaceHistoryModel $history);

    public function build()
    {
        foreach ($this->historyRepositoryBuilder->fetchAll() as $event) {
            $this->addHistory($event);
        }
    }
}
