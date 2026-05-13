# UAT Plan - ksf_KnowledgeBase

## Document Information
- **Module**: ksf_KnowledgeBase
- **Version**: 1.0.0
- **Date**: 2026-05-13
- **Status**: Ready for UAT
- **Author**: KSFII Development Team

---

## 1. UAT Objectives

### 1.1 Purpose
Validate that ksf_KnowledgeBase correctly manages articles, categories, search, and feedback for the knowledge base system.

### 1.2 Objectives
1. Verify article creation and publication
2. Confirm category management
3. Validate search functionality
4. Test feedback system
5. Ensure proper integration with FA/WP

---

## 2. Test Scenarios

### 2.1 Article Management

#### UAT-KB-001: Create New Article
**Scenario**: Author creates a knowledge base article

**Preconditions**: Author logged in

**Test Steps**:
1. Navigate to Knowledge Base > New Article
2. Enter title: "How to Reset Your Password"
3. Enter content with formatting
4. Add summary: "Learn how to reset your account password"
5. Select category: "Account Management"
6. Add tags: password, security, account
7. Click "Save Draft"
8. Verify article saved with draft status

**Expected Result**: Draft article created

**Pass Criteria**: [ ] Saved [ ] Draft status [ ] Tags included

---

#### UAT-KB-002: Publish Article
**Scenario**: Author publishes a draft article

**Preconditions**: Draft article exists

**Test Steps**:
1. Open draft article
2. Click "Publish"
3. Verify status changes to published
4. Verify published date set
5. Verify slug generated
6. Navigate to public knowledge base
7. Verify article appears

**Expected Result**: Article published and visible

**Pass Criteria**: [ ] Status published [ ] Date set [ ] Visible publicly

---

#### UAT-KB-003: View Article
**Scenario**: User views a knowledge base article

**Preconditions**: Published article exists

**Test Steps**:
1. Navigate to Knowledge Base
2. Browse or search for article
3. Click on article
4. Verify full content displayed
5. Verify view count incremented
6. Verify feedback buttons visible
7. Verify related articles shown

**Expected Result**: Article displayed correctly

**Pass Criteria**: [ ] Content shown [ ] View counted [ ] Feedback available

---

#### UAT-KB-004: Edit Article
**Scenario**: Author updates an existing article

**Preconditions**: Published article exists

**Test Steps**:
1. Open article in edit mode
2. Update content
3. Add additional tags
4. Click "Save"
5. Verify changes saved
6. Verify updated_at timestamp changed

**Expected Result**: Article updated

**Pass Criteria**: [ ] Changes saved [ ] Timestamp updated [ ] Content reflects changes

---

### 2.2 Category Management

#### UAT-KB-005: View Category Tree
**Scenario**: Admin views knowledge base categories

**Preconditions**: Categories exist with hierarchy

**Test Steps**:
1. Navigate to Knowledge Base > Categories
2. View category tree
3. Verify parent/child relationships shown
4. Verify sort order respected
5. Verify icons display correctly

**Expected Result**: Hierarchical category view

**Pass Criteria**: [ ] Tree structure [ ] Proper nesting [ ] Icons shown

---

#### UAT-KB-006: Create Category
**Scenario**: Admin creates a new category

**Preconditions**: Admin logged in

**Test Steps**:
1. Navigate to Categories
2. Click "Add Category"
3. Enter name: "Troubleshooting"
4. Select parent: "Getting Started"
5. Select icon: "support"
6. Enter description
7. Set sort order: 5
8. Click "Save"
9. Verify category appears in tree

**Expected Result**: Category created

**Pass Criteria**: [ ] Created [ ] In tree [ ] Hierarchy correct

---

#### UAT-KB-007: Reorder Categories
**Scenario**: Admin changes category order

**Preconditions**: Categories exist

**Test Steps**:
1. Open category management
2. Drag category to new position
3. Or update sort_order field
4. Save changes
5. Verify order updated in display

**Expected Result**: Categories reordered

**Pass Criteria**: [ ] Order changed [ ] Persists [ ] Display updated

---

#### UAT-KB-008: Delete Empty Category
**Scenario**: Admin deletes unused category

**Preconditions**: Empty category exists

**Test Steps**:
1. Find category with no articles
2. Click delete
3. Confirm deletion
4. Verify category removed

**Expected Result**: Category deleted

**Pass Criteria**: [ ] Deleted [ ] Not in tree [ ] No errors

---

#### UAT-KB-009: Prevent Delete Non-Empty
**Scenario**: System prevents deleting category with content

**Preconditions**: Category has children or articles

**Test Steps**:
1. Find category with articles
2. Click delete
3. Verify error/warning shown
4. Verify category NOT deleted

**Expected Result**: Deletion prevented

**Pass Criteria**: [ ] Warning shown [ ] Category preserved [ ] Clear message

---

### 2.3 Search

#### UAT-KB-010: Search Articles
**Scenario**: User searches the knowledge base

**Preconditions**: Articles with various content exist

**Test Steps**:
1. Enter search box
2. Type: "password"
3. Press Enter
4. Verify results displayed
5. Verify articles with "password" in title first
6. Click result to view article

**Expected Result**: Relevant articles found

**Pass Criteria**: [ ] Results shown [ ] Relevant order [ ] Title matches first

---

#### UAT-KB-011: Search Multiple Terms
**Scenario**: User searches with multiple words

**Preconditions**: Articles exist

**Test Steps**:
1. Search: "password reset"
2. Verify results contain both terms
3. Verify no unrelated results

**Expected Result**: AND search behavior

**Pass Criteria**: [ ] Both terms matched [ ] No unrelated shown

---

#### UAT-KB-012: View Popular Articles
**Scenario**: User views most popular articles

**Preconditions**: Articles with views exist

**Test Steps**:
1. Navigate to Knowledge Base
2. Click "Popular" or "Most Viewed"
3. Verify articles listed by view count
4. Verify highest viewed first

**Expected Result**: Popular articles shown

**Pass Criteria**: [ ] Sorted correctly [ ] View counts shown [ ] Most popular first

---

#### UAT-KB-013: View Recent Articles
**Scenario**: User views latest articles

**Preconditions**: Recent articles published

**Test Steps**:
1. Navigate to "Recent" or "Latest"
2. Verify newest articles first
3. Verify publication dates shown

**Expected Result**: Recent articles displayed

**Pass Criteria**: [ ] Sorted by date [ ] Newest first [ ] Dates visible

---

### 2.4 Feedback

#### UAT-KB-014: Submit Helpful Feedback
**Scenario**: User finds article helpful

**Preconditions**: Article viewed

**Test Steps**:
1. Read article
2. Click "Helpful" button
3. (Optional) Add comment
4. Verify success message
5. Verify article helpful count incremented

**Expected Result**: Feedback recorded

**Pass Criteria**: [ ] Recorded [ ] Count updated [ ] Message shown

---

#### UAT-KB-015: Submit Not Helpful Feedback
**Scenario**: User indicates article needs improvement

**Preconditions**: Article viewed

**Test Steps**:
1. Read article
2. Click "Not Helpful" button
3. Enter comment explaining issue
4. Submit
5. Verify feedback recorded
6. Verify article not_helpful count incremented

**Expected Result**: Critical feedback captured

**Pass Criteria**: [ ] Comment saved [ ] Count updated [ ] Notification sent

---

#### UAT-KB-016: View Feedback Stats
**Scenario**: Admin reviews article feedback

**Preconditions**: Feedback exists

**Test Steps**:
1. Open article admin view
2. View feedback statistics
3. Verify helpful percentage shown
4. View individual feedback entries
5. Review comments

**Expected Result**: Stats visible

**Pass Criteria**: [ ] % calculated [ ] Comments shown [ ] Breakdown clear

---

### 2.5 Integration

#### UAT-KB-017: FA Module Integration
**Scenario**: Verify FA integration works

**Preconditions**: ksf_FA_KnowledgeBase installed

**Test Steps**:
1. Access via FrontAccounting
2. Verify articles accessible
3. Verify category tree works
4. Verify all CRUD operations

**Expected Result**: FA integration functional

**Pass Criteria**: [ ] Menu accessible [ ] Features working [ ] No errors

---

#### UAT-KB-018: WordPress Shortcode
**Scenario**: Display KB articles in WordPress

**Preconditions**: WordPress integration configured

**Test Steps**:
1. Add [kb_article id=1] to WP page
2. View page
3. Verify article displayed
4. Add [kb_search] to page
5. Verify search box appears

**Expected Result**: Shortcodes working

**Pass Criteria**: [ ] Article renders [ ] Search works [ ] Styling consistent

---

## 3. Test Execution Schedule

### 3.1 Phase 1: Articles (Day 1)
| Test | Focus |
|------|-------|
| UAT-KB-001 | Article creation |
| UAT-KB-002 | Publishing |
| UAT-KB-003 | Viewing |
| UAT-KB-004 | Editing |

### 3.2 Phase 2: Categories (Day 1)
| Test | Focus |
|------|-------|
| UAT-KB-005 | Tree view |
| UAT-KB-006 | Create category |
| UAT-KB-007 | Reorder |
| UAT-KB-008 | Delete |
| UAT-KB-009 | Prevent delete |

### 3.3 Phase 3: Search & Feedback (Day 2)
| Test | Focus |
|------|-------|
| UAT-KB-010 | Search |
| UAT-KB-011 | Multi-term |
| UAT-KB-012 | Popular |
| UAT-KB-013 | Recent |
| UAT-KB-014 | Helpful feedback |
| UAT-KB-015 | Not helpful feedback |
| UAT-KB-016 | Stats view |

### 3.4 Phase 4: Integration (Day 2)
| Test | Focus |
|------|-------|
| UAT-KB-017 | FA integration |
| UAT-KB-018 | WP shortcodes |

---

## 4. Success Criteria

### 4.1 Functional Criteria

| Criteria | Target | Actual |
|----------|--------|--------|
| Article CRUD | 100% | - |
| Category management | 100% | - |
| Search | 100% | - |
| Feedback | 100% | - |
| Integration | 100% | - |

### 4.2 Test Summary

| Category | Total | Passed | Failed |
|----------|-------|--------|--------|
| Articles | 4 | - | - |
| Categories | 5 | - | - |
| Search | 4 | - | - |
| Feedback | 3 | - | - |
| Integration | 2 | - | - |
| **Total** | **18** | **-** | **-** |

---

## 5. Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Content Manager | | | |
| QA Lead | | | |
| Technical Lead | | | |

---

## 6. Appendix

### 6.1 Test Data

| Type | Value |
|------|-------|
| Article Title | "How to Reset Your Password" |
| Category | "Account Management" |
| Tags | password, security, account |
| Search Term | "password" |

### 6.2 Sample Category Structure

```
Knowledge Base
├── Getting Started
│   ├── Account Setup
│   └── First Steps
├── Account Management
│   ├── Password Reset
│   └── Profile Settings
└── Troubleshooting
    ├── Common Issues
    └── Error Messages
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-13*