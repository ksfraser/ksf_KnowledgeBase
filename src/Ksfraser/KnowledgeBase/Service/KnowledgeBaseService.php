<?php

namespace Ksfraser\KnowledgeBase\Service;

use Ksfraser\KnowledgeBase\Entity\KBArticle;
use Ksfraser\KnowledgeBase\Entity\KBCategory;
use Ksfraser\KnowledgeBase\Entity\KBFeedback;

class KnowledgeBaseService
{
    private $fa_path;

    public function __construct()
    {
        $this->fa_path = defined('KSF_FA_PATH') ? KSF_FA_PATH : '/var/www/html';
    }

    public function getArticle(int $id): ?KBArticle
    {
        return KBArticle::find($id);
    }

    public function getArticleBySlug(string $slug): ?KBArticle
    {
        return KBArticle::findBySlug($slug);
    }

    public function getCategory(int $id): ?KBCategory
    {
        return KBCategory::find($id);
    }

    public function getCategoryBySlug(string $slug): ?KBCategory
    {
        return KBCategory::findBySlug($slug);
    }

    public function search(string $query, int $limit = 20): array
    {
        $this->init_fa();

        $search_terms = explode(' ', trim($query));
        $conditions = [];

        foreach ($search_terms as $term) {
            $term = db_escape($term);
            $conditions[] = "(title LIKE '%{$term}%' OR content LIKE '%{$term}%')";
        }

        $sql = "SELECT * FROM " . TB_PREF . "kb_articles 
            WHERE status = 'published' 
            AND (" . implode(' AND ', $conditions) . ")
            ORDER BY 
                CASE 
                    WHEN title LIKE '%" . db_escape($query) . "%' THEN 1
                    ELSE 2
                END,
                hits DESC
            LIMIT " . db_escape($limit);

        $result = db_query($sql, "Could not search articles");
        $articles = [];

        while ($row = db_fetch_assoc($result)) {
            $articles[] = new KBArticle($row);
        }

        return $articles;
    }

    public function getPopularArticles(int $limit = 10): array
    {
        return KBArticle::mostViewed($limit);
    }

    public function getRecentArticles(int $limit = 10): array
    {
        return KBArticle::recent($limit);
    }

    public function getRelatedArticles(KBArticle $article, int $limit = 5): array
    {
        return KBArticle::findRelated($article->id, $article->category_id, $limit);
    }

    public function getCategoryTree(): array
    {
        $roots = KBCategory::roots();
        return array_map(fn($cat) => $this->buildCategoryTree($cat), $roots);
    }

    private function buildCategoryTree(KBCategory $category): array
    {
        $node = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->name,
            'icon' => $category->icon,
        ];

        if ($category->hasChildren()) {
            $node['children'] = array_map(
                fn($child) => $this->buildCategoryTree($child),
                $category->children()
            );
        }

        return $node;
    }

    public function submitFeedback(int $article_id, string $rating, ?string $comment = null): KBFeedback
    {
        $data = [
            'article_id' => $article_id,
            'rating' => $rating,
            'comment' => $comment,
        ];

        if (function_exists('is_user_logged_in') && is_user_logged_in()) {
            $user = wp_get_current_user();
            $data['user_id'] = $user->ID;
        } else {
            $data['session_id'] = $this->get_session_id();
        }

        return KBFeedback::create($data);
    }

    public function getFeedbackStats(int $article_id): array
    {
        return KBFeedback::getStats($article_id);
    }

    public function recordArticleView(int $article_id)
    {
        $this->init_fa();
        $sql = "UPDATE " . TB_PREF . "kb_articles 
            SET hits = hits + 1 
            WHERE id = " . db_escape($article_id);
        db_query($sql, "Could not record view");
    }

    public function createCategory(array $data): KBCategory
    {
        $this->init_fa();

        $slug = $this->generateSlug($data['name']);
        $data['slug'] = $slug;

        $sql = "INSERT INTO " . TB_PREF . "kb_categories
            (name, slug, description, parent_id, sort_order, is_published, icon, created_at)
            VALUES (
                " . db_escape($data['name']) . ",
                " . db_escape($slug) . ",
                " . db_escape($data['description'] ?? '') . ",
                " . db_escape($data['parent_id'] ?? null) . ",
                " . db_escape($data['sort_order'] ?? 0) . ",
                " . db_escape($data['is_published'] ?? 0) . ",
                " . db_escape($data['icon'] ?? '') . ",
                NOW()
            )";

        db_query($sql, "Could not create category");

        $category = new KBCategory($data);
        $category->id = db_insert_id();

        return $category;
    }

    public function updateCategory(int $id, array $data): ?KBCategory
    {
        $this->init_fa();

        $updates = [];
        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'description', 'parent_id', 'sort_order', 'is_published', 'icon'])) {
                $updates[] = "$key = " . db_escape($value);
            }
        }

        if (!empty($updates)) {
            $updates[] = "updated_at = NOW()";
            $sql = "UPDATE " . TB_PREF . "kb_categories 
                SET " . implode(', ', $updates) . "
                WHERE id = " . db_escape($id);
            db_query($sql, "Could not update category");
        }

        return KBCategory::find($id);
    }

    public function deleteCategory(int $id): bool
    {
        $category = KBCategory::find($id);
        if (!$category) {
            return false;
        }

        if ($category->hasChildren()) {
            return false;
        }

        $this->init_fa();
        $sql = "DELETE FROM " . TB_PREF . "kb_categories 
            WHERE id = " . db_escape($id);
        db_query($sql, "Could not delete category");

        return true;
    }

    public function createArticle(array $data): KBArticle
    {
        $this->init_fa();

        $slug = $this->generateSlug($data['title']);
        $data['slug'] = $slug;

        $sql = "INSERT INTO " . TB_PREF . "kb_articles
            (title, slug, content, summary, category_id, status, tags, author, created_at)
            VALUES (
                " . db_escape($data['title']) . ",
                " . db_escape($slug) . ",
                " . db_escape($data['content'] ?? '') . ",
                " . db_escape($data['summary'] ?? '') . ",
                " . db_escape($data['category_id']) . ",
                " . db_escape($data['status'] ?? 'draft') . ",
                " . db_escape($data['tags'] ?? '') . ",
                " . db_escape($data['author'] ?? 'admin') . ",
                NOW()
            )";

        db_query($sql, "Could not create article");

        $article = new KBArticle($data);
        $article->id = db_insert_id();

        return $article;
    }

    public function updateArticle(int $id, array $data): ?KBArticle
    {
        $this->init_fa();

        $updates = [];
        foreach ($data as $key => $value) {
            if (in_array($key, ['title', 'content', 'summary', 'category_id', 'status', 'tags'])) {
                $updates[] = "$key = " . db_escape($value);
            }
        }

        if (!empty($updates)) {
            $updates[] = "updated_at = NOW()";
            $sql = "UPDATE " . TB_PREF . "kb_articles 
                SET " . implode(', ', $updates) . "
                WHERE id = " . db_escape($id);
            db_query($sql, "Could not update article");
        }

        return KBArticle::find($id);
    }

    public function publishArticle(int $id): ?KBArticle
    {
        return $this->updateArticle($id, ['status' => 'published']);
    }

    public function unpublishArticle(int $id): ?KBArticle
    {
        return $this->updateArticle($id, ['status' => 'draft']);
    }

    public function deleteArticle(int $id): bool
    {
        $this->init_fa();
        $sql = "DELETE FROM " . TB_PREF . "kb_articles 
            WHERE id = " . db_escape($id);
        db_query($sql, "Could not delete article");

        return true;
    }

    private function generateSlug(string $title): string
    {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    private function get_session_id(): string
    {
        if (!session_id()) {
            session_start();
        }

        return session_id();
    }

    private function init_fa()
    {
        if (!file_exists($this->fa_path . '/includes/db.inc')) {
            throw new \RuntimeException('FA not configured');
        }
        global $db;
        include_once $this->fa_path . '/includes/db.inc';
    }
}