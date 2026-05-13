# Business Requirements - ksf_KnowledgeBase

## Document Information
- **Module**: ksf_KnowledgeBase
- **Version**: 1.0.0
- **Date**: 2026-05-13
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Project Overview

### 1.1 Purpose
The ksf_KnowledgeBase module provides comprehensive knowledge base management functionality including article creation, category organization, user feedback tracking, and search capabilities. It integrates with FrontAccounting and WordPress platforms.

### 1.2 Business Problem Statement
Organizations need to share knowledge and documentation with users and staff. The ksf_KnowledgeBase module provides:
- Article management with draft/publish workflow
- Hierarchical category organization
- User feedback (helpful/not helpful)
- Full-text search
- View tracking and analytics
- Multi-platform integration (FA, WP)

### 1.3 Scope

| Category | Included |
|----------|----------|
| Article Management | Yes |
| Category Organization | Yes |
| Feedback System | Yes |
| Search Functionality | Yes |
| View Tracking | Yes |
| Draft/Publish Workflow | Yes |
| Multi-language | No (Future) |

---

## 2. Module Architecture

### 2.1 Namespace Structure
```
Ksfraser\KnowledgeBase\
├── Entity\
│   ├── KBArticle.php      # Article entity
│   ├── KBCategory.php     # Category entity
│   └── KBFeedback.php     # Feedback entity
└── Service\
    └── KnowledgeBaseService.php # Business logic
```

### 2.2 Core Entities

#### KBArticle Entity
Represents knowledge base articles:

| Property | Type | Description |
|----------|------|-------------|
| id | ?int | Primary key |
| title | string | Article title |
| content | string | Article body (HTML/markdown) |
| summary | string | Short description |
| categoryId | int | Parent category |
| tags | array | Search tags |
| status | string | draft, published |
| authorId | int | Author reference |
| publishedDate | ?string | Publication date |
| views | int | View counter |
| helpful | int | Helpful votes |
| notHelpful | int | Not helpful votes |

#### KBCategory Entity
Hierarchical category organization:

| Property | Type | Description |
|----------|------|-------------|
| id | int | Primary key |
| name | string | Category name |
| description | string | Category description |
| parentId | ?int | Parent category |
| sortOrder | int | Display order |
| icon | string | Icon identifier |
| isPublic | bool | Visibility |

#### KBFeedback Entity
User feedback tracking:

| Property | Type | Description |
|----------|------|-------------|
| id | int | Primary key |
| articleId | int | Related article |
| userId | ?int | User (if logged in) |
| sessionId | ?string | Session (if anonymous) |
| rating | string | helpful, not_helpful, neutral |
| comment | ?string | Optional comment |
| createdAt | string | Timestamp |

---

## 3. Functional Features

### 3.1 Article Management

| Feature | Description |
|---------|-------------|
| Create | Create new articles |
| Edit | Modify existing articles |
| Delete | Remove articles |
| Publish | Change status to published |
| Unpublish | Change status to draft |
| View Tracking | Increment view counter |

### 3.2 Category Management

| Feature | Description |
|---------|-------------|
| Create Category | Add new category |
| Edit Category | Modify category |
| Delete Category | Remove (if empty) |
| Hierarchy | Parent-child relationships |
| Sort Order | Manual ordering |
| Public/Private | Visibility control |

### 3.3 Search

| Feature | Description |
|---------|-------------|
| Full-text Search | Search title and content |
| Tag Search | Search by tags |
| Category Filter | Filter by category |
| Relevance Ranking | Prioritize title matches |
| Popular Articles | Most viewed articles |
| Recent Articles | Latest published |

### 3.4 Feedback System

| Rating | Constant | Description |
|--------|----------|-------------|
| Helpful | RATING_HELPFUL | Article was useful |
| Not Helpful | RATING_NOT_HELPFUL | Article needs improvement |
| Neutral | RATING_NEUTRAL | No strong opinion |

### 3.5 Statistics

| Metric | Description |
|--------|-------------|
| Views | Total article views |
| Helpful % | (helpful / total) * 100 |
| Popular Articles | Top viewed |
| Recent Articles | Latest published |

---

## 4. Integration Dependencies

### 4.1 Depends On

| Module | Dependency Type | Purpose |
|--------|-----------------|---------|
| FrontAccounting | Required | Database, authentication |
| ksf_FA_KnowledgeBase | UI Adapter | FA integration |

### 4.2 Provided To

| Module | Data/Events |
|--------|-------------|
| ksf_FA_KnowledgeBase | Articles, categories |
| WordPress | Articles (via shortcode) |

### 4.3 WordPress Integration

| Feature | Description |
|---------|-------------|
| is_user_logged_in() | Check WP auth |
| wp_get_current_user() | Get WP user |
| Shortcodes | [kb_article], [kb_search] |

---

## 5. Database Schema

### 5.1 Tables

| Table | Purpose |
|-------|---------|
| kb_articles | Article content and metadata |
| kb_categories | Category hierarchy |
| kb_feedback | User feedback ratings |

### 5.2 Article Table Structure

```sql
CREATE TABLE `{PREFIX}kb_articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `content` TEXT,
    `summary` VARCHAR(500),
    `category_id` INT,
    `status` ENUM('draft','published') DEFAULT 'draft',
    `tags` VARCHAR(255),
    `author` VARCHAR(64),
    `hits` INT DEFAULT 0,
    `helpful` INT DEFAULT 0,
    `not_helpful` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_hits` (`hits`)
);
```

---

## 6. User Interactions

### 6.1 Anonymous Users
- Browse categories
- Read articles
- Submit feedback (by session)
- Search articles

### 6.2 Logged-in Users
- All anonymous features
- Feedback tied to user ID
- (Future: Create/edit articles)

### 6.3 Administrators
- Full CRUD on articles
- Full CRUD on categories
- View feedback analytics
- Reorder categories

---

## 7. Non-Functional Requirements

### 7.1 Performance
- Search: < 200ms
- Article load: < 100ms
- Category tree: < 50ms

### 7.2 Scalability
- Support 10,000+ articles
- Efficient category queries

---

## 8. Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Business Analyst | | | |
| Technical Lead | | | |
| QA Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-13*