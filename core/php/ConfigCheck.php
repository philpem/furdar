<?php



/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ConfigCheck
{
    
    /** @var Config **/
    protected $config;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    
    public function getErrors($field)
    {
        $out = array();
        
        if (in_array($field, array('webIndexDomain','webSiteDomain')) && $this->config->isSingleSiteMode && $this->config->webIndexDomain != $this->config->webSiteDomain) {
            $out[] = 'In single site mode, webIndexDomain and webSiteDomain should be the same!';
        }
        
        if ($field == 'emailFrom' && !filter_var($this->config->emailFrom, FILTER_VALIDATE_EMAIL)) {
            $out[] = 'This must be a valid email address!';
        }
        
        if ($field == 'contactEmail' && !filter_var($this->config->contactEmail, FILTER_VALIDATE_EMAIL)) {
            $out[] = 'This must be a valid email address!';
        }
        
        if ($field == 'logToStdError' && $this->config->logToStdError && !$this->config->logFile) {
            $out[] = 'For logToStdError to work logFile must be set';
        }

        if ($field == 'taskUpdateVenueFutureEventsCacheAutomaticUpdateInterval' && $this->config->taskUpdateVenueFutureEventsCacheAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskSendUserWatchesNotifyAutomaticUpdateInterval' && $this->config->taskSendUserWatchesNotifyAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateAreaFutureEventsCacheAutomaticUpdateInterval' && $this->config->taskUpdateAreaFutureEventsCacheAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateAreaBoundsCacheAutomaticUpdateInterval' && $this->config->taskUpdateAreaBoundsCacheAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateAreaParentCacheAutomaticUpdateInterval' && $this->config->taskUpdateAreaParentCacheAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateSiteCacheAutomaticUpdateInterval' && $this->config->taskUpdateSiteCacheAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateAreaHistoryChangeFlagsAutomaticUpdateInterval' && $this->config->taskUpdateAreaHistoryChangeFlagsAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateEventHistoryChangeFlagsAutomaticUpdateInterval' && $this->config->taskUpdateEventHistoryChangeFlagsAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateGroupHistoryChangeFlagsAutomaticUpdateInterval' && $this->config->taskUpdateGroupHistoryChangeFlagsAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateImportURLHistoryChangeFlagsAutomaticUpdateInterval' && $this->config->taskUpdateImportURLHistoryChangeFlagsAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateSiteHistoryChangeFlagsAutomaticUpdateInterval' && $this->config->taskUpdateSiteHistoryChangeFlagsAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateTagHistoryChangeFlagsAutomaticUpdateInterval' && $this->config->taskUpdateTagHistoryChangeFlagsAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        if ($field == 'taskUpdateHistoryChangeFlagsTaskAutomaticUpdateInterval' && $this->config->taskUpdateHistoryChangeFlagsTaskAutomaticUpdateInterval < 1) {
            $out[] = 'This task is disabled';
        }

        $logLevels = array('emergency','alert','critical','warning','notice','info','debug','error');
        if ($field == 'logLevel' && !in_array($this->config->logLevel, $logLevels)) {
            $out[] = 'This is not a recognised level! Try: '. implode(", ", $logLevels);
        }

        return $out;
    }
}
