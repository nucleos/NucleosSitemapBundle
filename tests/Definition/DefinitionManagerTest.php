<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Definition;

use Core23\SitemapBundle\Definition\DefintionManager;
use Core23\SitemapBundle\Definition\DefintionManagerInterface;
use Core23\SitemapBundle\Definition\SitemapDefinition;
use PHPUnit\Framework\TestCase;

class DefinitionManagerTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $definition = new DefintionManager();

        $this->assertInstanceOf(DefintionManagerInterface::class, $definition);
    }

    public function testAddDefintion(): void
    {
        $definition = new DefintionManager();
        $definition->addDefinition('foo.definition', [
            'foo' => 'bar',
        ]);

        foreach ($definition->getAll() as $id =>  $item) {
            $this->assertInstanceOf(SitemapDefinition::class, $item);
            $this->assertSame('foo.definition', $id);
            $this->assertSame([
                'foo' => 'bar',
            ], $item->getSettings());
        }
    }
}
