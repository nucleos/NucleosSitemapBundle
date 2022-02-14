<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\Generator;

use DateTime;
use Nucleos\SitemapBundle\Definition\DefintionManagerInterface;
use Nucleos\SitemapBundle\Generator\SitemapGenerator;
use Nucleos\SitemapBundle\Model\Url;
use Nucleos\SitemapBundle\Model\UrlInterface;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceInterface;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SitemapBundle\Tests\Fixtures\InvalidArgumentException;
use Nucleos\SitemapBundle\Tests\Fixtures\SitemapDefinitionStub;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;

final class SitemapGeneratorTest extends TestCase
{
    /**
     * @var MockObject&SitemapServiceManagerInterface
     */
    private $sitemapServiceManager;

    /**
     * @var DefintionManagerInterface&MockObject
     */
    private $defintionManager;

    public static function setUpBeforeClass(): void
    {
        date_default_timezone_set('UTC');
    }

    protected function setUp(): void
    {
        $this->sitemapServiceManager = $this->createMock(SitemapServiceManagerInterface::class);
        $this->defintionManager      = $this->createMock(DefintionManagerInterface::class);
    }

    public function testToXMLWithInvalidDefinition(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('foo');

        $this->sitemapServiceManager->method('get')->with($definition)
            ->willReturn(null)
        ;

        $this->defintionManager->method('getAll')
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager,
            $this->defintionManager
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

        $this->defintionManager->method('getAll')
            ->willReturn([])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager,
            $this->defintionManager
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXML(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '<url><loc>http://nucleos.rocks</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('foo');

        $url = $this->createMock(UrlInterface::class);
        $url->method('getChangeFreq')
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->method('getLastMod')
            ->willReturn(new DateTime('2017-12-23 00:00:00'))
        ;
        $url->method('getLoc')
            ->willReturn('http://nucleos.rocks')
        ;
        $url->method('getPriority')
            ->willReturn(80)
        ;

        $sitemap = $this->createMock(SitemapServiceInterface::class);
        $sitemap->method('execute')->with($definition)
            ->willReturn([
                $url,
            ])
        ;

        $this->sitemapServiceManager->method('get')->with($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->method('getAll')
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager,
            $this->defintionManager
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithExistingCache(): void
    {
        $xmlEntry = '<url><loc>http://nucleos.rocks</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= $xmlEntry;
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('foo');

        $url = $this->createMock(UrlInterface::class);
        $url->method('getChangeFreq')
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->method('getLastMod')
            ->willReturn(new DateTime('2017-12-23 00:00:00'))
        ;
        $url->method('getLoc')
            ->willReturn('http://nucleos.rocks')
        ;
        $url->method('getPriority')
            ->willReturn(80)
        ;

        $sitemap = $this->createMock(SitemapServiceInterface::class);

        $this->sitemapServiceManager->method('get')->with($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->method('getAll')
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('has')->with(static::stringStartsWith('Sitemap_'))
            ->willReturn(true)
        ;
        $cache->method('get')->with(static::stringStartsWith('Sitemap_'))
            ->willReturn($xmlEntry)
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager,
            $this->defintionManager,
            $cache
        );

        static::assertSame($expected, $generator->toXML());
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testToXMLWithExpiredCache(): void
    {
        $xmlEntry = '<url><loc>http://nucleos.rocks</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= $xmlEntry;
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('example');

        $url = $this->createMock(UrlInterface::class);
        $url->method('getChangeFreq')
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->method('getLastMod')
            ->willReturn(new DateTime('2017-12-23'))
        ;
        $url->method('getLoc')
            ->willReturn('http://nucleos.rocks')
        ;
        $url->method('getPriority')
            ->willReturn(80)
        ;

        $sitemap = $this->createMock(SitemapServiceInterface::class);
        $sitemap->method('execute')->with($definition)
            ->willReturn([
                $url,
            ])
        ;

        $this->sitemapServiceManager->method('get')->with($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->method('getAll')
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('has')->with(static::stringStartsWith('Sitemap_'))
            ->willReturn(false)
        ;
        $cache->method('set')->with(static::stringStartsWith('Sitemap_'), $xmlEntry, 42)
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager,
            $this->defintionManager,
            $cache
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithCacheException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error accessing cache');

        $definition = new SitemapDefinitionStub('example');

        $this->sitemapServiceManager->method('get')->with($definition)
            ->willReturn(null)
        ;

        $this->defintionManager->method('getAll')
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('has')->with(static::stringStartsWith('Sitemap_'))
            ->willThrowException(new InvalidArgumentException())
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager,
            $this->defintionManager,
            $cache
        );

        $generator->toXML();
    }
}
