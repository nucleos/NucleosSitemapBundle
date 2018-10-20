<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SitemapNotFoundException extends NotFoundHttpException
{
}
