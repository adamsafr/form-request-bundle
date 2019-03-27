<?php

namespace Adamsafr\FormRequestBundle\Tests\Locator;

use Adamsafr\FormRequestBundle\Locator\FormRequestServiceLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

class FormRequestServiceLocatorTest extends \PHPUnit\Framework\TestCase
{
    public function testIsSubclassOfBaseServiceLocator()
    {
        $locator = new FormRequestServiceLocator([]);

        $this->assertTrue($locator instanceof ServiceLocator);
    }
}
