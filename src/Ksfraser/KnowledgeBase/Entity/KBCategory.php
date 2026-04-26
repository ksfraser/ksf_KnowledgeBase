<?php

namespace Ksfraser\KnowledgeBase\Entity;

class KBCategory
{
    public int $id;
    public string $name;
    public ?string $description;
    public ?int $parent_id;
    public int $sort_order = 0;
    public bool $is_published = false;
    public ?string $icon;
    public string $created_at;
    public ?string $updated_at;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? 0;
            $this->name = $data['name'];
            $this->description = $data['description'] ?? null;
            $this->parent_id = $data['parent_id'] ?? null;
            $this->sort_order = $data['sort_order'] ?? 0;
            $this->is_published = $data['is_published'] ?? false;
            $this->icon = $data['icon'] ?? null;
            $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
            $this->updated_at = $data['updated_at'] ?? null;
        }
    }

    public function articles(): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_articles 
            WHERE category_id = " . db_escape($this->id) . " 
            AND status = 'published'
            ORDER BY created_at DESC";

        $result = db_query($sql, "Could not get articles");
        $articles = [];

        while ($row = db_fetch_assoc($result)) {
            $articles[] = new KBArticle($row);
        }

        return $articles;
    }

    public function children(): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_categories 
            WHERE parent_id = " . db_escape($this->id) . "
            ORDER BY sort_order";

        $result = db_query($sql, "Could not get children");
        $categories = [];

        while ($row = db_fetch_assoc($result)) {
            $categories[] = new KBCategory($row);
        }

        return $categories;
    }

    public function hasChildren(): bool
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT COUNT(*) as cnt FROM " . TB_PREF . "kb_categories 
            WHERE parent_id = " . db_escape($this->id);

        $result = db_query($sql, "Could not check children");
        $row = db_fetch_assoc($result);

        return $row && $row['cnt'] > 0;
    }

    public function getPath(): array
    {
        $path = [$this];
        $current = $this;

        while ($current->parent_id) {
            $parent = self::find($current->parent_id);
            if ($parent) {
                array_unshift($path, $parent);
                $current = $parent;
            } else {
                break;
            }
        }

        return $path;
    }

    public static function find(int $id): ?self
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_categories WHERE id = " . db_escape($id);
        $result = db_query($sql, "Could not find category");
        $row = db_fetch_assoc($result);

        return $row ? new self($row) : null;
    }

    public static function all(): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_categories ORDER BY sort_order";
        $result = db_query($sql, "Could not get categories");
        $categories = [];

        while ($row = db_fetch_assoc($result)) {
            $categories[] = new self($row);
        }

        return $categories;
    }

    public static function roots(): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_categories 
            WHERE parent_id IS NULL 
            ORDER BY sort_order";

        $result = db_query($sql, "Could not get root categories");
        $categories = [];

        while ($row = db_fetch_assoc($result)) {
            $categories[] = new self($row);
        }

        return $categories;
    }

    public static function published(): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_categories 
            WHERE is_published = 1 
            ORDER BY sort_order";

        $result = db_query($sql, "Could not get published categories");
        $categories = [];

        while ($row = db_fetch_assoc($result)) {
            $categories[] = new self($row);
        }

        return $categories;
    }
}