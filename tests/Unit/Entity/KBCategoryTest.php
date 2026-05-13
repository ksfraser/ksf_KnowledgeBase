<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\KnowledgeBase\Entity;

use Ksfraser\KnowledgeBase\Entity\KBCategory;
use PHPUnit\Framework\TestCase;

class KBCategoryTest extends TestCase
{
    public function testCreateCategory(): void
    {
        $category = new KBCategory();
        $category->setId('cat-001');
        $category->setName('Getting Started');
        $category->setDescription('Initial setup guides');

        $this->assertSame('cat-001', $category->getId());
        $this->assertSame('Getting Started', $category->getName());
    }

    public function testSetParent(): void
    {
        $parent = new KBCategory();
        $parent->setId('cat-parent');

        $category = new KBCategory();
        $category->setParentId('cat-parent');

        $this->assertSame('cat-parent', $category->getParentId());
    }
}
