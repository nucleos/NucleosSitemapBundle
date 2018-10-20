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
use Doctrine\Common\Cache\Cache;

final class SitemapGenerator implements SitemapGeneratorInterface
{
    /**
     * @var Cache|null
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
     * @param Cache|null                     $cache
     */
    public function __construct(SitemapServiceManagerInterface $serviceManager, DefintionManagerInterface $sitemapManager, Cache $cache = null)
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
     * @param SitemapDefinitionInterface $sitemap
     *
     * @return string
     */
    private function fetch(SitemapDefinitionInterface $sitemap): string
    {
        $name = md5(serialize($sitemap));

        if ($this->cache && $this->cache->contains($name)) {
            return $this->cache->fetch($name);
        }

        $service = $this->serviceManager->get($sitemap);

        if (!$service) {
            return '';
        }

        $xml = '';
        foreach ($service->execute($sitemap) as $entry) {
            $xml .= $this->getLocEntry($entry);
        }

        if ($this->cache) {
            $this->cache->save($name, $xml, $sitemap->getTtl());
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
