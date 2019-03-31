<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\DependencyInjection;

use Core23\SitemapBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
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

        $this->assertSame($expected, $config);
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
            ],
        ];

        $this->assertArraySubset($expected, $config);
    }
}
