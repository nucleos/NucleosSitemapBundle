<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Model;

interface SitemapInterface
{
    /**
     * Sets the id.
     *
     * @param string|null $id
     */
    public function setId(?string $id): void;

    /**
     * Returns the id.
     *
     * @return string
     */
    public function getId(): ?string;

    /**
     * Sets the type.
     *
     * @param string|null $type
     */
    public function setType(?string $type): void;

    /**
     * Returns the type.
     *
     * @return string|null $type
     */
    public function getType(): ?string;

    /**
     * Returns the block cache TTL.
     *
     * @return int
     */
    public function getTtl(): int;

    /**
     * Sets the block settings.
     *
     * @param array $settings An array of key/value
     */
    public function setSettings(array $settings = []): void;

    /**
     * Returns the block settings.
     *
     * @return array $settings An array of key/value
     */
    public function getSettings(): array;

    /**
     * Sets one block setting.
     *
     * @param string $name  Key name
     * @param mixed  $value Value
     */
    public function setSetting(string $name, $value): void;

    /**
     * Returns one block setting or the given default value if no value is found.
     *
     * @param string     $name    Key name
     * @param mixed|null $default Default value
     *
     * @return mixed
     */
    public function getSetting(string $name, $default = null);
}
