<?php

namespace import;

use models\ImportModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class ImportHandlerBase
{


    /** @var Application */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /** @var ImportRun **/
    protected $importRun;
    
    public function setImportRun(ImportRun $importRun)
    {
        $this->importRun = $importRun;
    }
    
    abstract public function canHandle();
    
    abstract public function handle();
        
    
    /**
     *
     *
     *
     * @return boolean
     */
    public function isStopAfterHandling()
    {
        return true;
    }
    
    
    /**
     *
     * Handlers are sorted into order before running.
     *
     * Lower values are run first.
     *
     * @return int
     */
    public function getSortOrder()
    {
        return 0;
    }
}
