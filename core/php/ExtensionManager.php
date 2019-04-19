<?php


use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


class ExtensionManager
{
    protected $extensions = array();
    
    protected $coreExtension;
            
    public function __construct(Application $app)
    {
        $this->coreExtension = new \ExtensionCore($app);
    }
    
    public function addExtension($dir, BaseExtension $extension)
    {
        $bits = explode("/", $dir);
        $this->extensions[array_pop($bits)] = $extension;
    }
    
    public function getExtensions()
    {
        return $this->extensions;
    }
    
    public function getExtensionsIncludingCore()
    {
        return array_merge(array($this->coreExtension), $this->extensions);
    }
    
    public function getExtensionByDir($dir)
    {
        return $this->extensions[$dir];
    }
        
    public function getCoreExtension()
    {
        return $this->coreExtension;
    }
    
    public function getExtensionById($id)
    {
        if ($this->coreExtension->getId() == $id) {
            return $this->coreExtension;
        }
        foreach ($this->extensions as $extension) {
            if ($extension->getId() == $id) {
                return $extension;
            }
        }
    }

    public function hasExtensionID($id)
    {
        if ($this->coreExtension->getId() == $id) {
            return true;
        }
        foreach ($this->extensions as $extension) {
            if ($extension->getId() == $id) {
                return true;
            }
        }
        return false;
    }
}
