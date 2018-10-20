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
     * @param string|null $changeFreq
     *
     * @return self
     */
    public function setChangeFreq(?string $changeFreq);

    /**
     * @return \DateTime|null
     */
    public function getLastMod(): ?\DateTime;

    /**
     * @param \DateTime|null $lastMod
     *
     * @return self
     */
    public function setLastMod(?\DateTime $lastMod);

    /**
     * @return string
     */
    public function getLoc(): ?string;

    /**
     * @param string|null $loc
     *
     * @return self
     */
    public function setLoc(?string $loc);

    /**
     * @return int|null
     */
    public function getPriority(): ?int;

    /**
     * @param int|null $priority
     *
     * @return self
     */
    public function setPriority(?int $priority);
}
