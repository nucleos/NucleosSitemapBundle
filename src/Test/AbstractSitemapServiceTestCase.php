<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Test;

use Core23\SitemapBundle\Model\SitemapDefinitionInterface;
use Core23\SitemapBundle\Model\UrlInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractSitemapServiceTestCase extends TestCase
{
    protected $router;

    /**
     * @var SitemapServiceInterface
     */
    protected $service;

    /**
     * @var array[]
     */
    private $urls = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);

        $this->service = $this->createService();
    }

    /**
     * @return SitemapServiceInterface
     */
    abstract protected function createService(): SitemapServiceInterface;

    /**
     * @param SitemapDefinitionInterface $sitemap
     */
    final protected function process(SitemapDefinitionInterface $sitemap): void
    {
        /* @var UrlInterface[] $urls */
        $result = $this->service->execute($sitemap);

        $count = \count($this->urls);
        $this->assertCount($count, $result);

        if ($count > 0) {
            /** @var UrlInterface $url */
            foreach ($result as $url) {
                if ($data = &$this->containsUrl($url)) {
                    if ($url->getPriority() !== $data['priority']) {
                        throw new AssertionFailedError(sprintf("The url '%s' was expected with %s priority. %s given.", $url->getLoc(), $data['priority'], $url->getPriority()));
                    } elseif ($url->getChangeFreq() !== $data['changefreq']) {
                        throw new AssertionFailedError(sprintf("The url '%s' was expected with %s changefreq. %s given.", $url->getLoc(), $data['changefreq'], $url->getChangeFreq()));
                    } elseif ($url->getLastMod() != $data['lastmod']) {
                        throw new AssertionFailedError(sprintf("The url '%s' was expected with a different lastmod.", $url->getLoc()));
                    }
                    ++$data['count'];

                    continue;
                }
                throw new AssertionFailedError(sprintf("The url '%s' was not expected to be called.", $url->getLoc()));
            }
        }

        foreach ($this->urls as $data) {
            if (0 === $data['count']) {
                throw new AssertionFailedError(sprintf("The url '%s' was expected to be called actually was not called", $data['location']));
            }
        }
    }

    /**
     * @param string         $location
     * @param int            $priority
     * @param string         $changeFreq
     * @param \DateTime|null $lastMod
     */
    final protected function assertSitemap(string $location, int $priority, string $changeFreq, \DateTime $lastMod = null): void
    {
        $this->urls[] = ['location' => $location, 'priority' => $priority, 'changefreq' => $changeFreq, 'lastmod' => $lastMod, 'count' => 0];
    }

    /**
     * @param UrlInterface $url
     *
     * @return array|null
     */
    private function &containsUrl(UrlInterface $url): ?array
    {
        foreach ($this->urls as &$data) {
            if ($url->getLoc() === $data['location']) {
                return $data;
            }
        }

        return null;
    }
}
