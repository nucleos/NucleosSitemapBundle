<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Exception;

use Core23\SitemapBundle\Exception\SitemapNotFoundException;
use PHPUnit\Framework\TestCase;
use Throwable;

final class SitemapNotFoundExceptionTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $exception = new SitemapNotFoundException();

        static::assertInstanceOf(Throwable::class, $exception);
    }
}
