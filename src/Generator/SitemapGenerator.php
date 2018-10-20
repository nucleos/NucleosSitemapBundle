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

final class SitemapGenerator implements SitemapGeneratorInterface
{
    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * @var SitemapServiceManagerInterface
     */
    private $serviceManager;

    /**
     * @var DefintionManagerInterface
     */
    private $sitemapManager;

    /**
     * @param SitemapServiceManagerInterface $serviceManager
     * @param DefintionManagerInterface      $sitemapManager
     * @param CacheInterface|null            $cache
     */
    public function __construct(SitemapServiceManagerInterface $serviceManager, DefintionManagerInterface $sitemapManager, CacheInterface $cache = null)
    {
        $this->serviceManager = $serviceManager;
        $this->sitemapManager = $sitemapManager;
        $this->cache          = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function toXML(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $xml .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        foreach ($this->sitemapManager->getAll() as $sitemap) {
            $serviceXml = $this->fetch($sitemap);

            if ($serviceXml) {
                $xml .= $serviceXml;
            }
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Get eventual cached data or generate whole sitemap.
     *
     * @param SitemapDefinitionInterface $definition
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function fetch(SitemapDefinitionInterface $definition): string
    {
        $name = sprintf('Sitemap_%s', md5(serialize($definition)));

        if ($this->cache && $this->cache->has($name)) {
            return $this->cache->get($name);
        }

        $service = $this->serviceManager->get($definition);

        if (!$service) {
            return '';
        }

        $xml = '';
        foreach ($service->execute($definition) as $entry) {
            $xml .= $this->getLocEntry($entry);
        }

        if ($this->cache) {
            $this->cache->set($name, $xml, $definition->getTtl());
        }

        return $xml;
    }

    /**
     * @param UrlInterface $url
     *
     * @return string
     */
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
