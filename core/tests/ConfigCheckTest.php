<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ConfigCheckTest extends \BaseAppTest
{
    public function testMultiSiteOK()
    {
        $config = new Config();
        $config->isSingleSiteMode = false;
        $config->webIndexDomain = 'www.test.com';
        $config->webSiteDomain = 'test.com';
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(0, count($configCheck->getErrors('webIndexDomain')));
        $this->assertEquals(0, count($configCheck->getErrors('webSiteDomain')));
    }
    
    public function testSingleSiteOK()
    {
        $config = new Config();
        $config->isSingleSiteMode = true;
        $config->webIndexDomain = 'test.com';
        $config->webSiteDomain = 'test.com';
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(0, count($configCheck->getErrors('webIndexDomain')));
        $this->assertEquals(0, count($configCheck->getErrors('webSiteDomain')));
    }
    
    public function testSingleSiteDifferentDomains()
    {
        $config = new Config();
        $config->isSingleSiteMode = true;
        $config->webIndexDomain = 'www.test.com';
        $config->webSiteDomain = 'test.com';
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(1, count($configCheck->getErrors('webIndexDomain')));
        $this->assertEquals(1, count($configCheck->getErrors('webSiteDomain')));
    }
    
    
    
    public function testEmailsOk()
    {
        $config = new Config();
        $config->emailFrom = 'test@test.com';
        $config->contactEmail = 'test@test.com';
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(0, count($configCheck->getErrors('emailFrom')));
        $this->assertEquals(0, count($configCheck->getErrors('contactEmail')));
    }
    
    
    public function testEmailsBad()
    {
        $config = new Config();
        $config->emailFrom = 'test@test';
        $config->contactEmail = 'testtest.com';
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(1, count($configCheck->getErrors('emailFrom')));
        $this->assertEquals(1, count($configCheck->getErrors('contactEmail')));
    }
    
    public function testLogOK()
    {
        $config = new Config();
        $config->logFile= '/tmp/log.txt';
        $config->logToStdError = true;
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(0, count($configCheck->getErrors('logFile')));
        $this->assertEquals(0, count($configCheck->getErrors('logToStdError')));
    }
    
    public function testLogBad()
    {
        $config = new Config();
        $config->logFile= null;
        $config->logToStdError = true;
        
        $configCheck = new ConfigCheck($config);
        
        $this->assertEquals(0, count($configCheck->getErrors('logFile')));
        $this->assertEquals(1, count($configCheck->getErrors('logToStdError')));
    }


    public function dataForTestLogLevelGood()
    {
        return array(
            array('error'),
            array('emergency'),
            array('alert'),
            array('critical'),
            array('warning'),
            array('notice'),
            array('info'),
            array('debug'),
        );
    }

    /**
     * @dataProvider dataForTestLogLevelGood
     */
    public function testLogLevelGood($set)
    {
        $config = new Config();
        $config->logFile = '/tmp/test.log';
        $config->logLevel = $set;

        $configCheck = new ConfigCheck($config);

        $this->assertEquals(0, count($configCheck->getErrors('logLevel')));
    }


    public function dataForTestLogLevelBad()
    {
        return array(
            array('ERROR'),
            array('emergency!!'),
            array('alert  '),
            array('cats'),
        );
    }

    /**
     * @dataProvider dataForTestLogLevelBad
     */
    public function testLogLevelBad($set)
    {
        $config = new Config();
        $config->logFile = '/tmp/test.log';
        $config->logLevel = $set;

        $configCheck = new ConfigCheck($config);

        $this->assertEquals(1, count($configCheck->getErrors('logLevel')));
    }
}
