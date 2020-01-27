<?php

namespace Adamsafr\FormRequestBundle\Tests\PHPUnit\Hooks;

use DG\BypassFinals;
use PHPUnit\Runner\BeforeTestHook;

/**
 * Class BypassFinalHook
 *
 * Borrowed from: https://www.tomasvotruba.com/blog/2019/03/28/how-to-mock-final-classes-in-phpunit/
 * Allows mocking SF final events introduced in SF 5+
 */
final class BypassFinalHook implements BeforeTestHook
{
    public function executeBeforeTest(string $test): void
    {
        BypassFinals::enable();
    }
}
