<?php
/**
 * This file does one thing; defines APP_ROOT_DIR as a constant. 
 * This allows the web roots and the app to be in different places.
 *
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
/** If the web roots are under the APP_ROOT_DIR use this. This is the default. **/
define('APP_ROOT_DIR',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);

/**
 * If you are using the vendor/, composer.json and composer.lock that came with 
 * this software COMPOSER_ROOT_DIR sholud be the same as APP_ROOT_DIR
 **/
define('COMPOSER_ROOT_DIR',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);

