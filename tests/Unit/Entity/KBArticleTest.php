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
        $article->setId('art-001');
        $article->setTitle('How to Reset Password');
        $article->setContent('Step 1: Click forgot password...');
        $article->setCategoryId('cat-001');
        $article->setAuthorId('user-001');
        $article->setStatus('published');

        $this->assertSame('art-001', $article->getId());
        $this->assertSame('How to Reset Password', $article->getTitle());
        $this->assertSame('published', $article->getStatus());
    }

    public function testPublishArticle(): void
    {
        $article = new KBArticle();
        $article->setStatus('draft');
        $article->publish();

        $this->assertSame('published', $article->getStatus());
    }

    public function testArchiveArticle(): void
    {
        $article = new KBArticle();
        $article->archive();

        $this->assertSame('archived', $article->getStatus());
    }

    public function testSetViewCount(): void
    {
        $article = new KBArticle();
        $article->setViewCount(100);

        $this->assertSame(100, $article->getViewCount());
    }

    public function testSetRating(): void
    {
        $article = new KBArticle();
        $article->setRating(4.5);

        $this->assertSame(4.5, $article->getRating());
    }
}
