<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Definition;

interface SitemapDefinitionInterface
{
    /**
     * Returns the type.
     *
     * @return string $type
     */
    public function getType(): string;

    /**
     * Returns the block cache TTL.
     */
    public function getTtl(): int;

    /**
     * Sets the block settings.
     *
     * @param array<string, mixed> $settings An array of key/value
     */
    public function setSettings(array $settings = []): void;

    /**
     * Returns the block settings.
     *
     * @return array<string, mixed> $settings An array of key/value
     */
    public function getSettings(): array;

    /**
     * Returns one block setting or the given default value if no value is found.
     *
     * @param string     $name    Key name
     * @param mixed|null $default Default value
     *
     * @return mixed|null
     */
    public function getSetting(string $name, $default = null);
}
