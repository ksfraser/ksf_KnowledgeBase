# Use Case - ksf_KnowledgeBase

## Document Information
- **Module**: ksf_KnowledgeBase
- **Version**: 1.0.0
- **Date**: 2026-05-13
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Use Case Overview

| Use Case ID | Use Case Name | Actor | Priority |
|-------------|---------------|-------|----------|
| UC-KB-001 | Browse Articles | User | High |
| UC-KB-002 | Search Knowledge Base | User | High |
| UC-KB-003 | View Article | User | High |
| UC-KB-004 | Submit Feedback | User | High |
| UC-KB-005 | Create Article | Author | High |
| UC-KB-006 | Publish Article | Author | High |
| UC-KB-007 | Manage Categories | Admin | Medium |
| UC-KB-008 | View Feedback Stats | Admin | Medium |

---

## 2. Use Case Details

### UC-KB-001: Browse Articles

**Actor**: User
**Priority**: High
**Preconditions**: Articles exist

**Basic Flow**:
1. User navigates to Knowledge Base
2. System displays categories and popular articles
3. User clicks category
4. System shows articles in category
5. User clicks article to view

**Postconditions**: Article displayed

---

### UC-KB-002: Search Knowledge Base

**Actor**: User
**Priority**: High
**Preconditions**: Articles exist

**Basic Flow**:
1. User enters search query
2. System searches title and content
3. System ranks results (title match higher)
4. System returns matching articles
5. User clicks result

**Postconditions**: Search results displayed

---

### UC-KB-003: View Article

**Actor**: User
**Priority**: High
**Preconditions**: Published article exists

**Basic Flow**:
1. User clicks article link
2. System fetches article
3. System increments view count
4. System displays article content
5. System shows feedback buttons
6. System shows related articles

**Postconditions**: Article view recorded

---

### UC-KB-004: Submit Feedback

**Actor**: User
**Priority**: High
**Preconditions**: Article viewed

**Basic Flow**:
1. User reads article
2. User clicks "Helpful" or "Not Helpful"
3. (Optional) User adds comment
4. System creates feedback record
5. System updates article counts
6. System shows confirmation

**Alternative Flows**:
- **Already feedback**: Show "Already submitted"
- **Not logged in**: Use session ID

**Postconditions**: Feedback recorded

---

### UC-KB-005: Create Article

**Actor**: Author
**Priority**: High
**Preconditions**: Author logged in

**Basic Flow**:
1. Author clicks "New Article"
2. System shows editor form
3. Author enters title, content, summary
4. Author selects category
5. Author adds tags
6. Author clicks "Save as Draft"
7. System creates article with status=draft

**Postconditions**: Article created, status draft

---

### UC-KB-006: Publish Article

**Actor**: Author
**Priority**: High
**Preconditions**: Draft article exists

**Basic Flow**:
1. Author opens draft article
2. Author clicks "Publish"
3. System changes status to published
4. System sets published_date
5. System generates slug
6. Article visible to users

**Postconditions**: Article published

---

### UC-KB-007: Manage Categories

**Actor**: Admin
**Priority**: Medium
**Preconditions**: Admin logged in

**Basic Flow**:
1. Admin opens Category Management
2. Views category tree
3. Creates/updates/deletes categories
4. Reorders categories
5. Changes category hierarchy

**Postconditions**: Categories updated

---

### UC-KB-008: View Feedback Stats

**Actor**: Admin
**Priority**: Medium
**Preconditions**: Feedback exists

**Basic Flow**:
1. Admin opens article
2. Views feedback section
3. System calculates statistics
4. Shows helpful %, total votes
5. Shows comments

**Postconditions**: Stats displayed

---

## 3. Sequence Diagrams

### UC-KB-002: Search Knowledge Base

```
User         UI           Service         Database
  │           │               │               │
  │ Enter query│               │               │
  │ "password" │               │               │
  │───────────>│               │               │
  │           │               │               │
  │           │ search("password", 20)        │
  │           │───────────────>│               │
  │           │               │               │
  │           │               │ Build query   │
  │           │               │ title LIKE%   │
  │           │               │ content LIKE% │
  │           │               │               │
  │           │               │ Execute       │
  │           │               │──────────────>│
  │           │               │               │
  │           │               │<───────────────│
  │           │               │ Results       │
  │           │<──────────────│               │
  │           │               │               │
  │ Results   │               │               │
  │<──────────│               │               │
```

### UC-KB-004: Submit Feedback

```
User         Article       Feedback       Database
  │             │              │              │
  │ Click "Helpful"            │              │
  │────────────>│              │              │
  │             │              │              │
  │             │ Check auth   │              │
  │             │ wp_get_current_user()       │
  │             │──────┐       │              │
  │             │      │ user │              │
  │             │<─────┘      │              │
  │             │              │              │
  │             │ create()     │              │
  │             │────────────>│              │
  │             │              │              │
  │             │              │ INSERT        │
  │             │              │──────────────>│
  │             │              │              │
  │             │              │<───────────────│
  │             │              │              │
  │             │ Update counts│              │
  │             │────────────>│              │
  │             │              │ UPDATE hits  │
  │             │              │──────────────>│
  │             │              │              │
  │ Success     │              │              │
  │<────────────│              │              │
```

---

## 4. Activity Diagram

### Article Publishing

```
[Start] ──> [Create/Edit Article]
                    │
                    ▼
           ┌───────────────────┐
           │ Fill Article      │
           │ Fields           │
           │ - Title          │
           │ - Content        │
           │ - Category       │
           │ - Tags           │
           └───────────────────┘
                    │
                    ▼
            ┌───────────────┐
            │ Save Draft?    │
            └───────────────┘
                 │     │
                Yes    No
                 │     │
                 ▼     ▼
         ┌──────────┐  [Publish]
         │Save Draft│      │
         └──────────┘      │
              │            │
              │            ▼
              │    ┌─────────────────┐
              │    │ Set status =    │
              │    │ published       │
              │    └─────────────────┘
              │            │
              ▼            ▼
    ┌──────────────────────┐
    │ Generate slug from   │
    │ title                │
    └──────────────────────┘
              │
              ▼
    ┌──────────────────────┐
    │ Set published_date   │
    └──────────────────────┘
              │
              ▼
          [Article Live]
              │
              ▼
            [End]
```

---

## 5. Use Case Traceability

| UC ID | Related FR | Related Test |
|-------|------------|-------------|
| UC-KB-001 | FR-KB-010, FR-KB-022 | KB-CAT-001 |
| UC-KB-002 | FR-KB-020, FR-KB-021 | KB-SRCH-001 |
| UC-KB-003 | FR-KB-001, FR-KB-005 | KB-ART-001 |
| UC-KB-004 | FR-KB-030, FR-KB-031, FR-KB-033 | KB-FB-001 |
| UC-KB-005 | FR-KB-001, FR-KB-040 | KB-ART-002 |
| UC-KB-006 | FR-KB-002, FR-KB-040 | KB-ART-003 |
| UC-KB-007 | FR-KB-010, FR-KB-011, FR-KB-015 | KB-CAT-002 |
| UC-KB-008 | FR-KB-031 | KB-FB-002 |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-13*