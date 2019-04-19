<?php
namespace api1exportbuilders;

use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
trait TraitCSV
{
    public function getResponse()
    {
        $response = new Response($this->getContents());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-disposition', 'attachment;filename=events.csv');
        $response->setPublic();
        $response->setMaxAge($this->app['config']->cacheFeedsInSeconds);
        return $response;
    }

    protected function getCell($in)
    {
        return '"'. str_replace(array('"'), array('""'), $in) .'"';
    }

    protected function getCellBoolean($in)
    {
        return $in ? "true" : "false";
    }
}
