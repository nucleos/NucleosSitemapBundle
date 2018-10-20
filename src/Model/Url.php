<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Model;

class Url implements UrlInterface
{
    /**
     * Always visit url.
     */
    public const FREQUENCE_ALWAYS = 'always';

    /**
     * Visit url every hour.
     */
    public const FREQUENCE_HOURLY = 'hourly';

    /**
     * Visit url every day.
     */
    public const FREQUENCE_DAILY = 'daily';

    /**
     * Visit url every week.
     */
    public const FREQUENCE_WEEKLY = 'weekly';

    /**
     * Visit url every month.
     */
    public const FREQUENCE_MONTHLY = 'monthly';

    /**
     * Visit url every year.
     */
    public const FREQUENCE_YEARLY = 'yearly';

    /**
     * Never visit url again.
     */
    public const FREQUENCE_NEVER = 'never';

    /**
     * @var string
     */
    protected $loc;

    /**
     * @var \DateTime|null
     */
    protected $lastMod;

    /**
     * @var string|null
     */
    protected $changeFreq;

    /**
     * @var int|null
     */
    protected $priority;

    /**
     * @param string         $loc
     * @param int|null       $priority
     * @param null|string    $changeFreq
     * @param \DateTime|null $lastMod
     */
    public function __construct(string $loc, ?int $priority = null, ?string $changeFreq = null, ?\DateTime $lastMod = null)
    {
        $this->loc        = $loc;
        $this->lastMod    = $lastMod;
        $this->changeFreq = $changeFreq;
        $this->priority   = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getChangeFreq(): ?string
    {
        return $this->changeFreq;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastMod(): ?\DateTime
    {
        return $this->lastMod;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoc(): string
    {
        return $this->loc;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }
}
