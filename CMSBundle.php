<?php

namespace alkr\CMSBundle;

use alkr\CMSBundle\Lib\Globals;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CMSBundle extends Bundle
{
	public function boot()
    {
    	$cms = $this->container->getParameter('cms');
        // Set some static globals
        Globals::setParams($cms);
    }
}
