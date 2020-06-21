<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\Fixtures;

use Exception;
use Psr\SimpleCache\InvalidArgumentException as PsrException;

final class InvalidArgumentException extends Exception implements PsrException
{
}
