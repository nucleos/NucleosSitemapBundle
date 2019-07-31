<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Generator;

use Core23\SitemapBundle\Definition\DefintionManagerInterface;
use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Model\UrlInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;

final class SitemapGenerator implements SitemapGeneratorInterface
{
    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * @var SitemapServiceManagerInterface
     */
    private $sitemapServiceManager;

    /**
     * @var DefintionManagerInterface
     */
    private $defintionManager;

    public function __construct(SitemapServiceManagerInterface $sitemapServiceManager, DefintionManagerInterface $defintionManager, CacheInterface $cache = null)
    {
        $this->sitemapServiceManager = $sitemapServiceManager;
        $this->defintionManager      = $defintionManager;
        $this->cache                 = $cache;
    }

    public function toXML(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $xml .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        foreach ($this->defintionManager->getAll() as $sitemap) {
            try {
                $xml .= $this->fetch($sitemap);
            } catch (InvalidArgumentException $exception) {
                throw new RuntimeException('Error accessing cache', $exception->getCode(), $exception);
            }
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Get eventual cached data or generate whole sitemap.
     *
     * @throws InvalidArgumentException
     */
    private function fetch(SitemapDefinitionInterface $definition): string
    {
        $name = sprintf('Sitemap_%s', md5(serialize($definition)));

        if (null !== $this->cache && $this->cache->has($name)) {
            return $this->cache->get($name);
        }

        $service = $this->sitemapServiceManager->get($definition);

        if (null === $service) {
            return '';
        }

        $xml = '';
        foreach ($service->execute($definition) as $entry) {
            $xml .= $this->getLocEntry($entry);
        }

        if (null !== $this->cache) {
            $this->cache->set($name, $xml, $definition->getTtl());
        }

        return $xml;
    }

    private function getLocEntry(UrlInterface $url): string
    {
        return '<url>'.
        '<loc>'.$url->getLoc().'</loc>'.
        (null !== $url->getLastMod() ? '<lastmod>'.$url->getLastMod()->format('c').'</lastmod>' : '').
        (null !== $url->getChangeFreq() ? '<changefreq>'.$url->getChangeFreq().'</changefreq>' : '').
        (null  !== $url->getPriority() ? '<priority>'.$url->getPriority().'</priority>' : '').
        '</url>';
    }
}
