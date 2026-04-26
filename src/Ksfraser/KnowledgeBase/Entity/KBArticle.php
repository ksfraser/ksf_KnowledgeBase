<?php

declare(strict_types=1);

namespace Ksfraser\KnowledgeBase\Entity;

class KBArticle
{
    private ?int $id = null;
    private string $title = '';
    private string $content = '';
    private string $summary = '';
    private int $categoryId = 0;
    private array $tags = [];
    private string $status = 'draft';
    private int $authorId = 0;
    private ?string $publishedDate = null;
    private int $views = 0;
    private int $helpful = 0;
    private int $notHelpful = 0;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getContent(): string { return $this->content; }
    public function setContent(string $content): self { $this->content = $content; return $this; }
    public function getSummary(): string { return $this->summary; }
    public function setSummary(string $summary): self { $this->summary = $summary; return $this; }
    public function getCategoryId(): int { return $this->categoryId; }
    public function setCategoryId(int $categoryId): self { $this->categoryId = $categoryId; return $this; }
    public function getTags(): array { return $this->tags; }
    public function setTags(array $tags): self { $this->tags = $tags; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }
    public function getAuthorId(): int { return $this->authorId; }
    public function setAuthorId(int $authorId): self { $this->authorId = $authorId; return $this; }
    public function getPublishedDate(): ?string { return $this->publishedDate; }
    public function setPublishedDate(?string $publishedDate): self { $this->publishedDate = $publishedDate; return $this; }
    public function getViews(): int { return $this->views; }
    public function setViews(int $views): self { $this->views = $views; return $this; }
    public function getHelpful(): int { return $this->helpful; }
    public function setHelpful(int $helpful): self { $this->helpful = $helpful; return $this; }
    public function getNotHelpful(): int { return $this->notHelpful; }
    public function setNotHelpful(int $notHelpful): self { $this->notHelpful = $notHelpful; return $this; }

    public function isPublished(): bool { return $this->status === 'published'; }
    public function isDraft(): bool { return $this->status === 'draft'; }

    public function incrementViews(): void { $this->views++; }
    public function markHelpful(): void { $this->helpful++; }
    public function markNotHelpful(): void { $this->notHelpful++; }

    public function getHelpfulPercent(): float
    {
        $total = $this->helpful + $this->notHelpful;
        return $total > 0 ? round(($this->helpful / $total) * 100, 1) : 0;
    }
}

class KBCategory
{
    private ?int $id = null;
    private string $name = '';
    private string $description = '';
    private ?int $parentId = null;
    private int $sortOrder = 0;
    private string $icon = 'book';
    private bool $isPublic = true;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }
    public function getParentId(): ?int { return $this->parentId; }
    public function setParentId(?int $parentId): self { $this->parentId = $parentId; return $this; }
    public function getSortOrder(): int { return $this->sortOrder; }
    public function setSortOrder(int $sortOrder): self { $this->sortOrder = $sortOrder; return $this; }
    public function getIcon(): string { return $this->icon; }
    public function setIcon(string $icon): self { $this->icon = $icon; return $this; }
    public function isPublic(): bool { return $this->isPublic; }
    public function setIsPublic(bool $isPublic): self { $this->isPublic = $isPublic; return $this; }

    public function isTopLevel(): bool { return $this->parentId === null; }
}

class KBFeedback
{
    private ?int $id = null;
    private int $articleId = 0;
    private int $userId = 0;
    private bool $helpful = false;
    private ?string $comment = null;
    private string $createdAt = '';

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getArticleId(): int { return $this->articleId; }
    public function setArticleId(int $articleId): self { $this->articleId = $articleId; return $this; }
    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $userId): self { $this->userId = $userId; return $this; }
    public function isHelpful(): bool { return $this->helpful; }
    public function setHelpful(bool $helpful): self { $this->helpful = $helpful; return $this; }
    public function getComment(): ?string { return $this->comment; }
    public function setComment(?string $comment): self { $this->comment = $comment; return $this; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function setCreatedAt(string $createdAt): self { $this->createdAt = $createdAt; return $this; }
}