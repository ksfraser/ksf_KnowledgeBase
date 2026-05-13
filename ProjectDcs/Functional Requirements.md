# Functional Requirements - ksf_KnowledgeBase

## Document Information
- **Module**: ksf_KnowledgeBase
- **Version**: 1.0.0
- **Date**: 2026-05-13
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Overview

This document details functional requirements for the ksf_KnowledgeBase module covering article management, categories, search, and feedback.

---

## 2. Article Management

### FR-KB-001: Create Article
**Priority**: High
**Description**: System shall create new knowledge base articles.

**Acceptance Criteria**:
- [ ] Title required, max 255 chars
- [ ] Content stored as HTML/markdown
- [ ] Summary optional, max 500 chars
- [ ] Category assignment optional
- [ ] Initial status: draft
- [ ] Slug auto-generated from title

**Test Data**:
| Input | Expected |
|-------|----------|
| title="How to Reset Password" | Article created |
| No title | Error: required |

---

### FR-KB-002: Article Status
**Priority**: High
**Description**: System shall track article publication status.

| Status | Description |
|--------|-------------|
| draft | Not visible to public |
| published | Visible to all |

**Acceptance Criteria**:
- [ ] isDraft() returns true for draft
- [ ] isPublished() returns true for published
- [ ] publishArticle() sets status to published
- [ ] unpublishArticle() sets status to draft

---

### FR-KB-003: Article Update
**Priority**: High
**Description**: System shall update existing articles.

**Acceptance Criteria**:
- [ ] updateArticle() modifies fields
- [ ] updated_at timestamp updated
- [ ] Only allowed fields updated
- [ ] Returns updated article

---

### FR-KB-004: Article Deletion
**Priority**: Medium
**Description**: System shall delete articles.

**Acceptance Criteria**:
- [ ] deleteArticle() removes from database
- [ ] Associated feedback not deleted (or cascade)
- [ ] Returns true on success

---

### FR-KB-005: View Tracking
**Priority**: Medium
**Description**: System shall count article views.

**Acceptance Criteria**:
- [ ] recordArticleView() increments hits
- [ ] Views visible in article data
- [ ] Only counts published articles (optional)

---

## 3. Category Management

### FR-KB-010: Create Category
**Priority**: High
**Description**: System shall create article categories.

**Acceptance Criteria**:
- [ ] Name required
- [ ] Slug auto-generated
- [ ] Parent optional (for hierarchy)
- [ ] Sort order configurable
- [ ] Published by default

---

### FR-KB-011: Category Hierarchy
**Priority**: High
**Description**: System shall support nested categories.

**Acceptance Criteria**:
- [ ] Parent ID links to parent category
- [ ] getPath() returns ancestry array
- [ ] getHierarchy() returns full tree
- [ ] hasChildren() checks for subcategories

---

### FR-KB-012: Category Root Nodes
**Priority**: Medium
**Description**: System shall identify top-level categories.

**Acceptance Criteria**:
- [ ] roots() returns categories with null parent
- [ ] isTopLevel() returns bool

---

### FR-KB-013: Category Children
**Priority**: Medium
**Description**: System shall retrieve subcategories.

**Acceptance Criteria**:
- [ ] children() returns array of child categories
- [ ] Only direct children returned
- [ ] Ordered by sort_order

---

### FR-KB-014: Category Update
**Priority**: Medium
**Description**: System shall update categories.

**Acceptance Criteria**:
- [ ] Name, description, icon, sort_order updatable
- [ ] Parent can be changed
- [ ] Cannot make category parent of itself

---

### FR-KB-015: Category Deletion
**Priority**: Medium
**Description**: System shall delete categories.

**Acceptance Criteria**:
- [ ] Cannot delete if has children
- [ ] Cannot delete if has articles (optional)
- [ ] Returns false on failure
- [ ] Returns true on success

---

## 4. Search

### FR-KB-020: Full-Text Search
**Priority**: High
**Description**: System shall search article content.

**Acceptance Criteria**:
- [ ] Search title and content
- [ ] Case-insensitive
- [ ] Multiple term support
- [ ] Results ordered by relevance

---

### FR-KB-021: Search Results
**Priority**: High
**Description**: System shall return relevant articles.

**Acceptance Criteria**:
- [ ] Only published articles returned
- [ ] Title matches ranked higher
- [ ] Limit parameter respected
- [ ] Results are KBArticle entities

---

### FR-KB-022: Popular Articles
**Priority**: Medium
**Description**: System shall return most-viewed articles.

**Acceptance Criteria**:
- [ ] mostViewed(n) returns top n by hits
- [ ] Only published articles
- [ ] Ordered by hits DESC

---

### FR-KB-023: Recent Articles
**Priority**: Medium
**Description**: System shall return latest articles.

**Acceptance Criteria**:
- [ ] recent(n) returns latest n
- [ ] Only published articles
- [ ] Ordered by created_at DESC

---

### FR-KB-024: Related Articles
**Priority**: Low
**Description**: System shall find related articles.

**Acceptance Criteria**:
- [ ] Same category articles
- [ ] Excludes current article
- [ ] Limited by parameter
- [ ] Ordered by relevance/popularity

---

## 5. Feedback System

### FR-KB-030: Submit Feedback
**Priority**: High
**Description**: System shall record user feedback.

**Acceptance Criteria**:
- [ ] Rating: helpful, not_helpful, neutral
- [ ] Linked to article
- [ ] User ID if logged in
- [ ] Session ID if anonymous
- [ ] Comment optional

---

### FR-KB-031: Feedback Stats
**Priority**: High
**Description**: System shall calculate feedback statistics.

**Acceptance Criteria**:
- [ ] Count by rating type
- [ ] Total feedback count
- [ ] Helpful percentage calculated

**Output Format**:
```php
[
    'helpful' => 45,
    'not_helpful' => 5,
    'neutral' => 10,
    'total' => 60,
    'helpful_pct' => 75.0,
]
```

---

### FR-KB-032: User Feedback Check
**Priority**: Medium
**Description**: System shall prevent duplicate feedback.

**Acceptance Criteria**:
- [ ] One feedback per user per article
- [ ] One feedback per session per article
- [ ] userFeedback() returns existing or null

---

### FR-KB-033: Article Rating Update
**Priority**: Medium
**Description**: System shall update article helpful counts.

**Acceptance Criteria**:
- [ ] markHelpful() increments helpful
- [ ] markNotHelpful() increments not_helpful
- [ ] Counts updated when feedback created

---

## 6. Slug Management

### FR-KB-040: Slug Generation
**Priority**: High
**Description**: System shall generate URL-friendly slugs.

**Algorithm**:
1. Convert to lowercase
2. Remove non-alphanumeric characters
3. Replace spaces with hyphens
4. Trim hyphens

**Acceptance Criteria**:
- [ ] URL-safe characters only
- [ ] Unique (database constraint)
- [ ] Reasonable length

---

## 7. Category Tree

### FR-KB-050: Build Category Tree
**Priority**: Medium
**Description**: System shall generate nested category structure.

**Acceptance Criteria**:
- [ ] getCategoryTree() returns nested array
- [ ] Each node has children array
- [ ] Includes id, name, slug, icon
- [ ] Recursive structure

---

## 8. Acceptance Test Matrix

| FR ID | Requirement | Test Cases | Status |
|-------|-------------|------------|--------|
| FR-KB-001 | Create Article | KB-ART-001 | ✓ |
| FR-KB-002 | Article Status | KB-ART-002 | ✓ |
| FR-KB-010 | Create Category | KB-CAT-001 | ✓ |
| FR-KB-011 | Category Hierarchy | KB-CAT-002 | ✓ |
| FR-KB-020 | Search | KB-SRCH-001 | ✓ |
| FR-KB-022 | Popular Articles | KB-SRCH-002 | ✓ |
| FR-KB-030 | Submit Feedback | KB-FB-001 | ✓ |
| FR-KB-031 | Feedback Stats | KB-FB-002 | ✓ |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-13*