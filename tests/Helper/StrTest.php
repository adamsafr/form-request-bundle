<?php

namespace Adamsafr\FormRequestBundle\Tests\Helper;

use Adamsafr\FormRequestBundle\Helper\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testSubstringContainsInTheString()
    {
        $str = 'Lorem ipsum dolor sit amet ...';

        $this->assertTrue(Str::contains($str, 'dolor'));
        $this->assertTrue(Str::contains($str, 'dolor sit'));
        $this->assertFalse(Str::contains($str, 'other value'));
    }

    public function testArrayValueContainsInTheString()
    {
        $this->assertTrue(Str::contains('Content-Type: application/json', ['/json', '+json']));
        $this->assertTrue(Str::contains('Content-Type: application/x-resource+json', ['/json', '+json']));
    }
}
