<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Fixtures;

use Exception;
use Psr\SimpleCache\InvalidArgumentException as PsrException;

final class InvalidArgumentException extends Exception implements PsrException
{
}
