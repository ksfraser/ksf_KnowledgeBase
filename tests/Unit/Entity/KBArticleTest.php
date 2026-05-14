<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\KnowledgeBase\Entity;

use Ksfraser\KnowledgeBase\Entity\KBArticle;
use PHPUnit\Framework\TestCase;

class KBArticleTest extends TestCase
{
    public function testCreateArticle(): void
    {
        $article = new KBArticle();
        $article->setId(1);
        $article->setTitle('How to Reset Password');
        $article->setContent('Step 1: Click forgot password...');
        $article->setCategoryId(1);
        $article->setAuthorId(1);
        $article->setStatus('published');

        $this->assertSame(1, $article->getId());
        $this->assertSame('How to Reset Password', $article->getTitle());
        $this->assertSame('published', $article->getStatus());
    }

    public function testPublishArticle(): void
    {
        $article = new KBArticle();
        $article->setStatus('published');

        $this->assertTrue($article->isPublished());
        $this->assertFalse($article->isDraft());
    }

    public function testSetViews(): void
    {
        $article = new KBArticle();
        $article->setViews(100);

        $this->assertSame(100, $article->getViews());
    }

    public function testIncrementViews(): void
    {
        $article = new KBArticle();
        $article->setViews(50);
        $article->incrementViews();

        $this->assertSame(51, $article->getViews());
    }

    public function testHelpfulVotes(): void
    {
        $article = new KBArticle();
        $article->setHelpful(10);
        $article->setNotHelpful(5);

        $this->assertSame(10, $article->getHelpful());
        $this->assertSame(5, $article->getNotHelpful());
        $this->assertEquals(66.7, $article->getHelpfulPercent());
    }
}
