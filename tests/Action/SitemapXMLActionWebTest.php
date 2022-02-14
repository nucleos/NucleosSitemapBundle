<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\Action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SitemapXMLActionWebTest extends WebTestCase
{
    public function testSitemapXml(): void
    {
        $client = static::createClient();

        $client->request('GET', '/sitemap.xml');

        $response = $client->getResponse();

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertResponseIsSuccessful();
        static::assertSame('text/xml; charset=UTF-8', $response->headers->get('Content-Type'));
        static::assertSame('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"><url><loc>example.com</loc></url><url><loc>/foo</loc><priority>50</priority></url><url><loc>/bar</loc><priority>75</priority></url></urlset>', $response->getContent());
    }
}
