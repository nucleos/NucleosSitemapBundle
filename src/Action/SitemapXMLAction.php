<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Action;

use Nucleos\SitemapBundle\Generator\SitemapGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;

final class SitemapXMLAction
{
    /**
     * @var SitemapGeneratorInterface
     */
    private $generator;

    public function __construct(SitemapGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function __invoke(): Response
    {
        $response = new Response($this->generator->toXML());
        $response->headers->set('Content-Type', 'text/xml');
        $response->setPublic();

        return $response;
    }
}
