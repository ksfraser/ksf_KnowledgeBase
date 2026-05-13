# Test Plan - ksf_KnowledgeBase

## Document Information
- **Module**: ksf_KnowledgeBase
- **Version**: 1.0.0
- **Date**: 2026-05-13
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Test Overview

### 1.1 Test Objectives
- Verify article CRUD operations
- Validate category management
- Confirm search functionality
- Test feedback system
- Ensure slug generation works

---

## 2. Test Cases

### 2.1 Article Tests

#### KB-ART-001: Create Article
**Test ID**: KB-ART-001
**Priority**: High

**Test Steps**:
1. Call createArticle with data
2. Assert article created
3. Assert id set
4. Assert slug generated
5. Assert status = "draft"

---

#### KB-ART-002: Find Article
**Test ID**: KB-ART-002
**Priority**: High

**Test Steps**:
1. Create article in database
2. Call KBArticle::find(id)
3. Assert returns entity
4. Assert all properties loaded

---

#### KB-ART-003: Publish Article
**Test ID**: KB-ART-003
**Priority**: High

**Test Steps**:
1. Create draft article
2. Call publishArticle(id)
3. Assert status = "published"
4. Assert publishedDate set

---

#### KB-ART-004: Article Status
**Test ID**: KB-ART-004
**Priority**: High

**Test Steps**:
1. Create article with status "draft"
2. Assert isDraft() === true
3. Assert isPublished() === false
4. Change status to "published"
5. Assert isPublished() === true

---

#### KB-ART-005: Increment Views
**Test ID**: KB-ART-005
**Priority**: Medium

**Test Steps**:
1. Create article with views = 10
2. Call incrementViews()
3. Assert views = 11

---

#### KB-ART-006: Helpful Percentage
**Test ID**: KB-ART-006
**Priority**: Medium

**Test Steps**:
1. Create article
2. Set helpful = 80, notHelpful = 20
3. Assert getHelpfulPercent() === 80.0
4. Set helpful = 0, notHelpful = 0
5. Assert getHelpfulPercent() === 0.0

---

### 2.2 Category Tests

#### KB-CAT-001: Create Category
**Test ID**: KB-CAT-001
**Priority**: High

**Test Steps**:
1. Call createCategory
2. Assert category created
3. Assert name set
4. Assert slug generated

---

#### KB-CAT-002: Category Hierarchy
**Test ID**: KB-CAT-002
**Priority**: High

**Test Steps**:
1. Create parent category
2. Create child category
3. Get child
4. Call getPath()
5. Assert array includes parent first

---

#### KB-CAT-003: Get Children
**Test ID**: KB-CAT-003
**Priority**: Medium

**Test Steps**:
1. Create category with 2 children
2. Call children()
3. Assert returns 2 categories
4. Assert both are direct children

---

#### KB-CAT-004: Root Categories
**Test ID**: KB-CAT-004
**Priority**: Medium

**Test Steps**:
1. Create root and child categories
2. Call roots()
3. Assert only root returned
4. Assert child not in results

---

#### KB-CAT-005: Category Tree
**Test ID**: KB-CAT-005
**Priority**: Medium

**Test Steps**:
1. Create nested structure
2. Call getCategoryTree()
3. Assert nested array returned
4. Assert children included recursively

---

### 2.3 Search Tests

#### KB-SRCH-001: Basic Search
**Test ID**: KB-SRCH-001
**Priority**: High

**Test Steps**:
1. Create articles with titles containing "password"
2. Call search("password")
3. Assert results returned
4. Assert results contain "password"

---

#### KB-SRCH-002: Multi-Term Search
**Test ID**: KB-SRCH-002
**Priority**: Medium

**Test Steps**:
1. Create article with "password reset" in content
2. Call search("password reset")
3. Assert article found
4. Assert AND logic (both terms)

---

#### KB-SRCH-003: Popular Articles
**Test ID**: KB-SRCH-003
**Priority**: Medium

**Test Steps**:
1. Create 3 articles with different hit counts
2. Call mostViewed(2)
3. Assert 2 articles returned
4. Assert ordered by hits DESC

---

#### KB-SRCH-004: Recent Articles
**Test ID**: KB-SRCH-004
**Priority**: Medium

**Test Steps**:
1. Create articles with different dates
2. Call recent(2)
3. Assert 2 articles returned
4. Assert ordered by created_at DESC

---

### 2.4 Feedback Tests

#### KB-FB-001: Submit Feedback
**Test ID**: KB-FB-001
**Priority**: High

**Test Steps**:
1. Call submitFeedback(article_id, "helpful", "Great article!")
2. Assert feedback created
3. Assert rating = "helpful"
4. Assert article counts updated

---

#### KB-FB-002: Feedback Stats
**Test ID**: KB-FB-002
**Priority**: High

**Test Steps**:
1. Add feedback: 10 helpful, 5 not_helpful, 5 neutral
2. Call getFeedbackStats(article_id)
3. Assert helpful = 10
4. Assert not_helpful = 5
5. Assert neutral = 5
6. Assert total = 20
7. Assert helpful_pct = 50.0

---

#### KB-FB-003: Duplicate Feedback Prevention
**Test ID**: KB-FB-003
**Priority**: Medium

**Test Steps**:
1. Submit feedback for user
2. Call userFeedback(article_id, null, user_id)
3. Assert returns existing feedback
4. Call again with different user
5. Assert returns null

---

#### KB-FB-004: Mark Helpful
**Test ID**: KB-FB-004
**Priority**: Medium

**Test Steps**:
1. Create article with helpful = 10
2. Call markHelpful()
3. Assert helpful = 11

---

### 2.5 Slug Tests

#### KB-SLUG-001: Slug Generation
**Test ID**: KB-SLUG-001
**Priority**: High

**Test Steps**:
1. Call generateSlug("How To Reset Password")
2. Assert equals "how-to-reset-password"
3. Call with special chars: "FAQ & Support"
4. Assert equals "faq-support"

---

### 2.6 Edge Cases

#### KB-EDGE-001: Article Not Found
**Test ID**: KB-EDGE-001
**Priority**: Low

**Test Steps**:
1. Call find(9999)
2. Assert returns null

---

#### KB-EDGE-002: Empty Search
**Test ID**: KB-EDGE-002
**Priority**: Low

**Test Steps**:
1. Call search("")
2. Assert returns empty array (or all published)

---

#### KB-EDGE-003: Category with Children
**Test ID**: KB-EDGE-003
**Priority**: Medium

**Test Steps**:
1. Create category with children
2. Call deleteCategory()
3. Assert returns false
4. Assert category still exists

---

## 3. Test Data

### 3.1 Articles

```php
$articles = [
    [
        'title' => 'How to Reset Password',
        'content' => 'Step 1: Go to settings...',
        'category_id' => 1,
        'status' => 'published',
        'tags' => 'password,security',
    ],
    [
        'title' => 'Account Setup Guide',
        'content' => 'Welcome to our platform...',
        'category_id' => 1,
        'status' => 'published',
    ],
];
```

### 3.2 Categories

```php
$categories = [
    ['name' => 'Getting Started', 'parent_id' => null],
    ['name' => 'Account Management', 'parent_id' => 1],
    ['name' => 'Billing', 'parent_id' => null],
];
```

### 3.3 Feedback

```php
$feedback = [
    ['article_id' => 1, 'rating' => 'helpful'],
    ['article_id' => 1, 'rating' => 'helpful'],
    ['article_id' => 1, 'rating' => 'not_helpful'],
];
```

---

## 4. Pass Criteria

| Category | Target |
|----------|--------|
| Article operations | 100% |
| Category operations | 100% |
| Search | 100% |
| Feedback | 100% |
| Slug generation | 100% |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-13*