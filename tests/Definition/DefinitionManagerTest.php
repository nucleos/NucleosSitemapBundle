<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Definition;

use Core23\SitemapBundle\Definition\DefintionManager;
use Core23\SitemapBundle\Definition\SitemapDefinition;
use PHPUnit\Framework\TestCase;

final class DefinitionManagerTest extends TestCase
{
    public function testAddDefintion(): void
    {
        $definition = new DefintionManager();
        $definition->addDefinition('foo.definition', [
            'foo' => 'bar',
        ]);

        foreach ($definition->getAll() as $id =>  $item) {
            static::assertInstanceOf(SitemapDefinition::class, $item);
            static::assertSame('foo.definition', $id);
            static::assertSame([
                'foo' => 'bar',
            ], $item->getSettings());
        }
    }
}
