<?php

namespace appconfiguration;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AppConfigurationManager
{
    
    /** @var PDO **/
    protected $DB;
    protected $CONFIG;
            
    public function __construct($DB, $CONFIG)
    {
        $this->DB = $DB;
        $this->CONFIG = $CONFIG;
    }

    public function getValue(AppConfigurationDefinition $config, string $default=null)
    {
        $stat = $this->DB->prepare("SELECT value_text FROM app_configuration_information ".
                "WHERE extension_id=:e AND configuration_key=:k");
        $stat->execute(array('e'=>$config->getExtensionId(),'k'=>$config->getKey()));
        $dbdata = $stat->fetch();
        
        if ($dbdata && $dbdata['value_text'] && ($config->isTypeText() || $config->isTypePassword())) {
            return $dbdata['value_text'];
        }
        
        return $default;
    }

    public function setValue(AppConfigurationDefinition $config, string $value)
    {
        $stat = $this->DB->prepare("SELECT value_text FROM app_configuration_information ".
            "WHERE extension_id=:e AND configuration_key=:k");
        $stat->execute(array(
                'e'=>$config->getExtensionId(),
                'k'=>$config->getKey()
            ));
        if ($stat->rowCount() == 1) {
            $stat = $this->DB->prepare("UPDATE app_configuration_information SET value_text=:text ".
                "WHERE extension_id=:e AND configuration_key=:k");
            $stat->execute(array(
                    'e'=>$config->getExtensionId(),
                    'k'=>$config->getKey(),
                    'text'=>$value,
                ));
        } else {
            $stat = $this->DB->prepare("INSERT INTO app_configuration_information  ".
                    "(extension_id,configuration_key,value_text) ".
                    "VALUES (:e,:k,:text)");
            $stat->execute(array(
                    'e'=>$config->getExtensionId(),
                    'k'=>$config->getKey(),
                    'text'=>$value,
                ));
        }
    }
}
