<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Model;

class Sitemap implements SitemapInterface
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var int
     */
    protected $ttl = 0;

    /**
     * @param string $type
     * @param array  $settings
     */
    public function __construct(string $type, array $settings = [])
    {
        $this->settings = $settings;
        $this->type     = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getType() ?: 'n/a';
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setSettings(array $settings = []): void
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * {@inheritdoc}
     */
    public function getSetting(string $name, $default = null)
    {
        return $this->settings[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getTtl(): int
    {
        if (!$this->getSetting('use_cache', true)) {
            return 0;
        }

        $ttl = $this->getSetting('ttl', 86400);

        $this->ttl = $ttl;

        return $this->ttl;
    }
}
