<?php

namespace tests\models;

use models\ImportModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportModelTest extends \BaseAppTest
{
    public function testGuessATitleIfMissingWhenNotMissing()
    {
        $importModel = new ImportModel();
        $importModel->setTitle("Title");
        $importModel->setUrl("http://google.com");
        $importModel->guessATitleIfMissing();

        $this->assertEquals("Title", $importModel->getTitle());
    }

    public function testGuessATitleIfMissingWhenMissing()
    {
        $importModel = new ImportModel();
        $importModel->setUrl("http://google.com");
        $importModel->guessATitleIfMissing();

        $this->assertEquals("http://google.com", $importModel->getTitle());
    }
}
