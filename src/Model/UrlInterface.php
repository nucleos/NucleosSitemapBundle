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
    /**
     * @return string|null
     */
    public function getChangeFreq(): ?string;

    /**
     * @return \DateTime|null
     */
    public function getLastMod(): ?\DateTime;

    /**
     * @return string
     */
    public function getLoc(): ?string;

    /**
     * @return int|null
     */
    public function getPriority(): ?int;
}
