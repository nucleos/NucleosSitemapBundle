<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Model;

interface UrlInterface
{
    public function getChangeFreq(): ?string;

    public function getLastMod(): ?\DateTime;

    /**
     * @return string
     */
    public function getLoc(): ?string;

    public function getPriority(): ?int;
}
