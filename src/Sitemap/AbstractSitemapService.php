<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Sitemap;

use Core23\SitemapBundle\Model\Url;
use Core23\SitemapBundle\Model\UrlInterface;
use DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @deprecated without any replacement
 */
abstract class AbstractSitemapService implements SitemapServiceInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
    }

    /**
     * @param string $name
     * @param int    $absolute
     */
    final protected function generate($name, array $parameters = [], $absolute = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->router->generate($name, $parameters, $absolute);
    }

    final protected function createEntry(string $location, ?int $priority, ?string $changeFreq = null, ?DateTime $lastMod = null): UrlInterface
    {
        return new Url($location, $priority, $changeFreq, $lastMod);
    }
}
