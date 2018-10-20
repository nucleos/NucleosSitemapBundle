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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractSitemapService implements SitemapServiceInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param string          $name
     * @param RouterInterface $router
     */
    public function __construct(string $name, RouterInterface $router)
    {
        $this->name   = $name;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param int    $absolute
     *
     * @return string
     */
    final protected function generate($name, array $parameters = [], $absolute = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->router->generate($name, $parameters, $absolute);
    }

    /**
     * @param string         $location
     * @param int|null       $priority
     * @param string|null    $changeFreq
     * @param \DateTime|null $lastMod
     *
     * @return UrlInterface
     */
    final protected function createEntry(string $location, ?int $priority, ?string $changeFreq = null, ?\DateTime $lastMod = null): UrlInterface
    {
        return new Url($location, $priority, $changeFreq, $lastMod);
    }
}
