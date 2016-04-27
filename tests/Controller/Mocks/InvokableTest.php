<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2016 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/master/LICENSE.md (MIT License)
 */
namespace Orx0r\Slim\Controller\Tests\Mocks;

/**
 * Mock object for Orx0r\Slim\Controller\Tests\CallableResolverTest
 */
class InvokableTest
{
    public static $CalledCount = 0;
    public function __invoke()
    {
        return static::$CalledCount++;
    }
}
