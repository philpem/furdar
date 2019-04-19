<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
interface InterfaceImportURLRecommendation
{
    public function getNewURL();

    public function getTitle();

    public function getDescription();

    public function getActionAcceptLabel();

    public function getActionRefuseLabel();

    public function getExtensionID();

    public function getRecommendationID();
}
