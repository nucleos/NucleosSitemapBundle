<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Generator;

use Core23\SitemapBundle\Definition\DefintionManagerInterface;
use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Generator\SitemapGenerator;
use Core23\SitemapBundle\Generator\SitemapGeneratorInterface;
use Core23\SitemapBundle\Model\Url;
use Core23\SitemapBundle\Model\UrlInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Core23\SitemapBundle\Tests\Fixtures\InvalidArgumentException;
use DateTime;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;

final class SitemapGeneratorTest extends TestCase
{
    private $sitemapServiceManager;

    private $defintionManager;

    public static function setUpBeforeClass()
    {
        date_default_timezone_set('UTC');
    }

    protected function setUp()
    {
        $this->sitemapServiceManager = $this->prophesize(SitemapServiceManagerInterface::class);
        $this->defintionManager      = $this->prophesize(DefintionManagerInterface::class);
    }

    public function testItIsInstantiable(): void
    {
        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertInstanceOf(SitemapGeneratorInterface::class, $generator);
    }

    public function testToXMLWithInvalidDefinition(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '</urlset>';

        $definition = $this->prophesize(SitemapDefinitionInterface::class);

        $this->sitemapServiceManager->get($definition)
            ->willReturn(null)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition->reveal(),
            ])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithNoEntries(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '</urlset>';

        $this->defintionManager->getAll()
            ->willReturn([])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXML(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '<url><loc>http://core23.de</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';
        $expected .= '</urlset>';

        $definition = $this->prophesize(SitemapDefinitionInterface::class);

        $url = $this->prophesize(UrlInterface::class);
        $url->getChangeFreq()
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->getLastMod()
            ->willReturn(new DateTime('2017-12-23 00:00:00'))
        ;
        $url->getLoc()
            ->willReturn('http://core23.de')
        ;
        $url->getPriority()
            ->willReturn(80)
        ;

        $sitemap = $this->prophesize(SitemapServiceInterface::class);
        $sitemap->execute($definition)
            ->willReturn([
                $url->reveal(),
            ])
        ;

        $this->sitemapServiceManager->get($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition->reveal(),
            ])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithExistingCache(): void
    {
        $xmlEntry = '<url><loc>http://core23.de</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= $xmlEntry;
        $expected .= '</urlset>';

        $definition = $this->prophesize(SitemapDefinitionInterface::class);
        $definition->getTtl()
            ->willReturn(90)
        ;

        $url = $this->prophesize(UrlInterface::class);
        $url->getChangeFreq()
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->getLastMod()
            ->willReturn(new DateTime('2017-12-23 00:00:00'))
        ;
        $url->getLoc()
            ->willReturn('http://core23.de')
        ;
        $url->getPriority()
            ->willReturn(80)
        ;

        $sitemap = $this->prophesize(SitemapServiceInterface::class);

        $this->sitemapServiceManager->get($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition->reveal(),
            ])
        ;

        $cache = $this->prophesize(CacheInterface::class);
        $cache->has(Argument::containingString('Sitemap_'))
            ->willReturn(true)
        ;
        $cache->get(Argument::containingString('Sitemap_'))
            ->willReturn($xmlEntry)
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal(),
            $cache->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithExpiredCache(): void
    {
        $xmlEntry = '<url><loc>http://core23.de</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= $xmlEntry;
        $expected .= '</urlset>';

        $definition = $this->prophesize(SitemapDefinitionInterface::class);
        $definition->getTtl()
            ->willReturn(90)
        ;

        $url = $this->prophesize(UrlInterface::class);
        $url->getChangeFreq()
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->getLastMod()
            ->willReturn(new DateTime('2017-12-23'))
        ;
        $url->getLoc()
            ->willReturn('http://core23.de')
        ;
        $url->getPriority()
            ->willReturn(80)
        ;

        $sitemap = $this->prophesize(SitemapServiceInterface::class);
        $sitemap->execute($definition)
            ->willReturn([
                $url->reveal(),
            ])
        ;

        $this->sitemapServiceManager->get($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition->reveal(),
            ])
        ;

        $cache = $this->prophesize(CacheInterface::class);
        $cache->has(Argument::containingString('Sitemap_'))
            ->willReturn(false)
        ;
        $cache->set(Argument::containingString('Sitemap_'), $xmlEntry, 90)
            ->shouldBeCalled()
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal(),
            $cache->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithCacheException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error accessing cache');

        $definition = $this->prophesize(SitemapDefinitionInterface::class);

        $this->sitemapServiceManager->get($definition)
            ->willReturn(null)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition->reveal(),
            ])
        ;

        $cache = $this->prophesize(CacheInterface::class);
        $cache->has(Argument::containingString('Sitemap_'))
            ->willThrow(InvalidArgumentException::class)
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal(),
            $cache->reveal()
        );

        $generator->toXML();
    }
}
