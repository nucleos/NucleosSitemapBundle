<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\DependencyInjection;

use Nucleos\SitemapBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testOptions(): void
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), [[
        ]]);

        $expected = [
            'static' => [
            ],
            'cache' => [
                'service' => null,
            ],
        ];

        static::assertSame($expected, $config);
    }

    public function testCronOptions(): void
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), [[
            'cache' => [
                'service' => 'acme.foo.service',
            ],
            'static' => [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
                [
                    'url'        => 'http://google.com',
                    'priority'   => 50,
                    'changefreq' => 'daily',
                ],
            ],
        ]]);

        $expected = [
            'cache' => [
                'service' => 'acme.foo.service',
            ],
            'static' => [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
                [
                    'url'        => 'http://google.com',
                    'priority'   => 50,
                    'changefreq' => 'daily',
                ],
            ],
        ];

        static::assertSame($expected, $config);
    }
}
