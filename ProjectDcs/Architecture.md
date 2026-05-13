# Architecture - ksf_KnowledgeBase

## Document Information
- **Module**: ksf_KnowledgeBase
- **Version**: 1.0.0
- **Date**: 2026-05-13
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Technical Architecture

### 1.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                     ksf_KnowledgeBase Module                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                         Entities                              │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │  KBArticle        KBCategory        KBFeedback              │   │
│  │  - CRUD           - Hierarchy       - Ratings               │   │
│  │  - Publish        - Tree             - Stats                │   │
│  │  - Stats          - Sort                                   │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    KnowledgeBaseService                      │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │  - Article CRUD    - Category CRUD    - Search             │   │
│  │  - Feedback        - View tracking    - Related articles    │   │
│  │  - Category tree   - Slug generation  - Popular/Recent      │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      FrontAccounting Platform                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                   │
│  │  Database   │  │   Auth      │  │   Hooks     │                   │
│  │             │  │             │  │             │                   │
│  └─────────────┘  └─────────────┘  └─────────────┘                   │
└─────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         WordPress Platform                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                   │
│  │  Shortcodes │  │   Users     │  │   Plugins   │                   │
│  │             │  │             │  │             │                   │
│  └─────────────┘  └─────────────┘  └─────────────┘                   │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.2 Class Diagram

```
┌────────────────────────────────────────────────────────────────────┐
│                            KBArticle                                │
├────────────────────────────────────────────────────────────────────┤
│ + id: ?int                                                         │
│ + title: string                                                    │
│ + content: string                                                   │
│ + summary: string                                                   │
│ + categoryId: int                                                   │
│ + tags: array                                                       │
│ + status: string                                                    │
│ + authorId: int                                                     │
│ + publishedDate: ?string                                           │
│ + views: int                                                        │
│ + helpful: int                                                      │
│ + notHelpful: int                                                   │
├────────────────────────────────────────────────────────────────────┤
│ + isPublished(): bool                                              │
│ + isDraft(): bool                                                  │
│ + incrementViews(): void                                           │
│ + markHelpful(): void                                              │
│ + markNotHelpful(): void                                           │
│ + getHelpfulPercent(): float                                       │
│ + static find(int): ?KBArticle                                      │
│ + static mostViewed(int): array                                    │
│ + static recent(int): array                                        │
│ + static findRelated(int, int, int): array                         │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│                            KBCategory                                │
├────────────────────────────────────────────────────────────────────┤
│ + id: int                                                           │
│ + name: string                                                      │
│ + description: string                                               │
│ + parentId: ?int                                                    │
│ + sortOrder: int                                                    │
│ + isPublished: bool                                                │
│ + icon: string                                                      │
│ + createdAt: string                                                 │
│ + updatedAt: ?string                                                │
├────────────────────────────────────────────────────────────────────┤
│ + isTopLevel(): bool                                               │
│ + articles(): array                                               │
│ + children(): array                                               │
│ + hasChildren(): bool                                             │
│ + getPath(): array                                                 │
│ + static find(int): ?KBCategory                                     │
│ + static all(): array                                              │
│ + static roots(): array                                             │
│ + static published(): array                                         │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│                            KBFeedback                               │
├────────────────────────────────────────────────────────────────────┤
│ + id: int                                                           │
│ + articleId: int                                                    │
│ + sessionId: ?string                                                │
│ + userId: ?int                                                      │
│ + rating: string                                                     │
│ + comment: ?string                                                  │
│ + createdAt: string                                                 │
├────────────────────────────────────────────────────────────────────┤
│ + RATING_HELPFUL = 'helpful'                                       │
│ + RATING_NOT_HELPFUL = 'not_helpful'                               │
│ + RATING_NEUTRAL = 'neutral'                                        │
│ + isPositive(): bool                                               │
│ + isNegative(): bool                                               │
│ + article(): ?KBArticle                                            │
│ + static find(int): ?KBFeedback                                    │
│ + static forArticle(int): array                                    │
│ + static getStats(int): array                                      │
│ + static create(array): KBFeedback                                 │
│ + static userFeedback(int, ?string, ?int): ?KBFeedback             │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│                       KnowledgeBaseService                           │
├────────────────────────────────────────────────────────────────────┤
│ - fa_path: string                                                   │
├────────────────────────────────────────────────────────────────────┤
│ + getArticle(int): ?KBArticle                                      │
│ + getArticleBySlug(string): ?KBArticle                             │
│ + getCategory(int): ?KBCategory                                    │
│ + getCategoryBySlug(string): ?KBCategory                            │
│ + search(string, int): array                                       │
│ + getPopularArticles(int): array                                   │
│ + getRecentArticles(int): array                                    │
│ + getRelatedArticles(KBArticle, int): array                        │
│ + getCategoryTree(): array                                         │
│ + submitFeedback(int, string, ?string): KBFeedback                  │
│ + getFeedbackStats(int): array                                     │
│ + recordArticleView(int): void                                     │
│ + createCategory(array): KBCategory                               │
│ + updateCategory(int, array): ?KBCategory                           │
│ + deleteCategory(int): bool                                       │
│ + createArticle(array): KBArticle                                  │
│ + updateArticle(int, array): ?KBArticle                             │
│ + publishArticle(int): ?KBArticle                                  │
│ + unpublishArticle(int): ?KBArticle                                │
│ + deleteArticle(int): bool                                         │
└────────────────────────────────────────────────────────────────────┘
```

---

## 2. Data Flow Diagrams

### 2.1 Article Search Flow

```
┌──────────┐    ┌──────────────┐    ┌────────────┐    ┌─────────────┐
│  User   │    │   Service    │    │   Search   │    │  Database   │
└──────────┘    └──────────────┘    └────────────┘    └─────────────┘
     │                 │                 │                │
     │ Enter search   │                 │                │
     │ "how to"       │                 │                │
     │────────────────>│                 │                │
     │                 │                 │                │
     │                 │ search("how to")                │
     │                 │───────────────>│                │
     │                 │                 │                │
     │                 │                 │ Build WHERE    │
     │                 │                 │ title LIKE OR  │
     │                 │                 │ content LIKE   │
     │                 │                 │                │
     │                 │                 │ Execute query  │
     │                 │                 │───────────────>│
     │                 │                 │                │
     │                 │                 │<───────────────│
     │                 │                 │ Results        │
     │                 │<───────────────│                │
     │                 │ Articles       │                │
     │ Results         │                │                │
     │<────────────────│                │                │
```

### 2.2 Feedback Submission Flow

```
┌──────────┐    ┌──────────────┐    ┌────────────┐    ┌─────────────┐
│  User   │    │   Service    │    │  Feedback  │    │  Database   │
└──────────┘    └──────────────┘    └────────────┘    └─────────────┘
     │                 │                 │                │
     │ Rate article   │                 │                │
     │ "helpful"      │                 │                │
     │────────────────>│                 │                │
     │                 │                 │                │
     │                 │ Check auth      │                │
     │                 │ WP logged_in    │                │
     │                 │──────┐          │                │
     │                 │      │ logged   │                │
     │                 │<─────┘          │                │
     │                 │                 │                │
     │                 │ Build data      │                │
     │                 │ user_id or      │                │
     │                 │ session_id      │                │
     │                 │                 │                │
     │                 │ create(data)    │                │
     │                 │────────────────>│                │
     │                 │                 │                │
     │                 │                 │ INSERT         │
     │                 │                 │───────────────>│
     │                 │                 │                │
     │                 │                 │<───────────────│
     │                 │                 │                │
     │                 │ Update stats    │                │
     │                 │ increment help  │                │
     │                 │───────────────>│                │
     │                 │                 │                │
     │ Success         │                 │                │
     │<────────────────│                 │                │
```

---

## 3. Database Schema

### 3.1 Articles Table

```sql
CREATE TABLE `{PREFIX}kb_articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `content` TEXT,
    `summary` VARCHAR(500),
    `category_id` INT,
    `status` ENUM('draft', 'published') DEFAULT 'draft',
    `tags` VARCHAR(255),
    `author` VARCHAR(64),
    `hits` INT DEFAULT 0,
    `helpful` INT DEFAULT 0,
    `not_helpful` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_hits` (`hits`),
    INDEX `idx_slug` (`slug`)
);
```

### 3.2 Categories Table

```sql
CREATE TABLE `{PREFIX}kb_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(64) NOT NULL,
    `slug` VARCHAR(64) UNIQUE,
    `description` TEXT,
    `parent_id` INT,
    `sort_order` INT DEFAULT 0,
    `is_published` TINYINT(1) DEFAULT 1,
    `icon` VARCHAR(32) DEFAULT 'book',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_parent` (`parent_id`),
    INDEX `idx_sort` (`sort_order`)
);
```

### 3.3 Feedback Table

```sql
CREATE TABLE `{PREFIX}kb_feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `article_id` INT NOT NULL,
    `session_id` VARCHAR(64),
    `user_id` INT,
    `rating` ENUM('helpful', 'not_helpful', 'neutral') NOT NULL,
    `comment` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_article` (`article_id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_session` (`session_id`)
);
```

---

## 4. API Design

### 4.1 KnowledgeBaseService API

```php
class KnowledgeBaseService
{
    public function __construct();
    
    // Article Operations
    public function getArticle(int $id): ?KBArticle;
    public function getArticleBySlug(string $slug): ?KBArticle;
    public function search(string $query, int $limit = 20): array;
    public function getPopularArticles(int $limit = 10): array;
    public function getRecentArticles(int $limit = 10): array;
    public function getRelatedArticles(KBArticle $article, int $limit = 5): array;
    public function recordArticleView(int $article_id): void;
    public function createArticle(array $data): KBArticle;
    public function updateArticle(int $id, array $data): ?KBArticle;
    public function publishArticle(int $id): ?KBArticle;
    public function unpublishArticle(int $id): ?KBArticle;
    public function deleteArticle(int $id): bool;
    
    // Category Operations
    public function getCategory(int $id): ?KBCategory;
    public function getCategoryBySlug(string $slug): ?KBCategory;
    public function getCategoryTree(): array;
    public function createCategory(array $data): KBCategory;
    public function updateCategory(int $id, array $data): ?KBCategory;
    public function deleteCategory(int $id): bool;
    
    // Feedback Operations
    public function submitFeedback(int $article_id, string $rating, ?string $comment = null): KBFeedback;
    public function getFeedbackStats(int $article_id): array;
}
```

### 4.2 KBArticle API

```php
class KBArticle
{
    // Properties
    public function getId(): ?int;
    public function setTitle(string $title): self;
    public function getContent(): string;
    public function setContent(string $content): self;
    public function isPublished(): bool;
    public function isDraft(): bool;
    public function getHelpfulPercent(): float;
    
    // Actions
    public function incrementViews(): void;
    public function markHelpful(): void;
    public function markNotHelpful(): void;
    
    // Static Methods
    public static function find(int $id): ?self;
    public static function mostViewed(int $limit): array;
    public static function recent(int $limit): array;
    public static function findRelated(int $id, int $categoryId, int $limit): array;
}
```

### 4.3 KBCategory API

```php
class KBCategory
{
    // Properties
    public function getName(): string;
    public function isTopLevel(): bool;
    
    // Relations
    public function articles(): array;
    public function children(): array;
    public function hasChildren(): bool;
    public function getPath(): array;
    
    // Static Methods
    public static function find(int $id): ?self;
    public static function all(): array;
    public static function roots(): array;
    public static function published(): array;
}
```

---

## 5. Search Implementation

### 5.1 Search Algorithm

```php
public function search(string $query, int $limit = 20): array
{
    // 1. Split query into terms
    $terms = explode(' ', trim($query));
    
    // 2. Build LIKE conditions for each term
    $conditions = [];
    foreach ($terms as $term) {
        $conditions[] = "(title LIKE '%{$term}%' OR content LIKE '%{$term}%')";
    }
    
    // 3. Combine with AND
    $where = "status = 'published' AND (" . implode(' AND ', $conditions) . ")";
    
    // 4. Order by title match first, then by hits
    $orderBy = "CASE WHEN title LIKE '%{$query}%' THEN 1 ELSE 2 END, hits DESC";
    
    // 5. Limit results
    return "SELECT * FROM kb_articles WHERE {$where} ORDER BY {$orderBy} LIMIT {$limit}";
}
```

### 5.2 Category Tree Building

```php
private function buildCategoryTree(KBCategory $category): array
{
    return [
        'id' => $category->id,
        'name' => $category->name,
        'slug' => $category->slug,
        'icon' => $category->icon,
        'children' => array_map(
            fn($child) => $this->buildCategoryTree($child),
            $category->children()
        ),
    ];
}
```

---

## 6. Error Handling

| Scenario | Handling |
|----------|----------|
| Article not found | Return null |
| Category not empty | Prevent delete, return false |
| Invalid rating | Validate against constants |
| DB error | Propagate exception |

---

## 7. Security Considerations

### 7.1 Input Sanitization
- All user inputs sanitized with db_escape()
- XSS prevention via output encoding
- SQL injection prevention via prepared statements

### 7.2 Access Control
- Draft articles only visible to authors/admins
- Published articles public
- Category visibility via is_published flag

### 7.3 Feedback Limits
- One feedback per user/session per article
- Comments optional
- Rate limiting recommended

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-13*