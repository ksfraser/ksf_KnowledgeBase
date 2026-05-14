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
        $category->setId(1);
        $category->setName('Getting Started');
        $category->setDescription('Initial setup guides');

        $this->assertSame(1, $category->getId());
        $this->assertSame('Getting Started', $category->getName());
    }

    public function testSetParent(): void
    {
        $parent = new KBCategory();
        $parent->setId(1);

        $category = new KBCategory();
        $category->setParentId(1);

        $this->assertSame(1, $category->getParentId());
    }
}
