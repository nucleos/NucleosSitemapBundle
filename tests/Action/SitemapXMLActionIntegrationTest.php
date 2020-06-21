<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\Action;

use Nucleos\SitemapBundle\Tests\App\AppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\Client;

final class SitemapXMLActionIntegrationTest extends TestCase
{
    public function testSitemapXml(): void
    {
        if (class_exists(KernelBrowser::class)) {
            $client = new KernelBrowser(new AppKernel());
        } else {
            $client = new Client(new AppKernel());
        }

        $client->request('GET', '/sitemap.xml');

        static::assertSame(200, $client->getResponse()->getStatusCode());
    }
}
