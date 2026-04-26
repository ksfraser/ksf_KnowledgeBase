<?php

namespace Ksfraser\KnowledgeBase\Entity;

class KBFeedback
{
    public int $id;
    public int $article_id;
    public ?string $session_id;
    public ?int $user_id;
    public string $rating;
    public ?string $comment;
    public string $created_at;

    public const RATING_HELPFUL = 'helpful';
    public const RATING_NOT_HELPFUL = 'not_helpful';
    public const RATING_NEutral = 'neutral';

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? 0;
            $this->article_id = $data['article_id'];
            $this->session_id = $data['session_id'] ?? null;
            $this->user_id = $data['user_id'] ?? null;
            $this->rating = $data['rating'];
            $this->comment = $data['comment'] ?? null;
            $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        }
    }

    public function article(): ?KBArticle
    {
        return KBArticle::find($this->article_id);
    }

    public function isPositive(): bool
    {
        return $this->rating === self::RATING_HELPFUL;
    }

    public function isNegative(): bool
    {
        return $this->rating === self::RATING_NOT_HELPFUL;
    }

    public static function find(int $id): ?self
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_feedback WHERE id = " . db_escape($id);
        $result = db_query($sql, "Could not find feedback");
        $row = db_fetch_assoc($result);

        return $row ? new self($row) : null;
    }

    public static function forArticle(int $article_id): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT * FROM " . TB_PREF . "kb_feedback 
            WHERE article_id = " . db_escape($article_id) . "
            ORDER BY created_at DESC";

        $result = db_query($sql, "Could not get feedback");
        $feedback = [];

        while ($row = db_fetch_assoc($result)) {
            $feedback[] = new self($row);
        }

        return $feedback;
    }

    public static function getStats(int $article_id): array
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "SELECT 
            rating, 
            COUNT(*) as count 
            FROM " . TB_PREF . "kb_feedback 
            WHERE article_id = " . db_escape($article_id) . "
            GROUP BY rating";

        $result = db_query($sql, "Could not get feedback stats");
        $stats = [
            'helpful' => 0,
            'not_helpful' => 0,
            'neutral' => 0,
            'total' => 0,
        ];

        while ($row = db_fetch_assoc($result)) {
            $stats[$row['rating']] = (int) $row['count'];
            $stats['total'] += (int) $row['count'];
        }

        if ($stats['total'] > 0) {
            $stats['helpful_pct'] = round($stats['helpful'] / $stats['total'] * 100, 1);
        } else {
            $stats['helpful_pct'] = 0;
        }

        return $stats;
    }

    public static function create(array $data): self
    {
        global $db;
        include_once '../../../includes/db.inc';

        $sql = "INSERT INTO " . TB_PREF . "kb_feedback
            (article_id, session_id, user_id, rating, comment, created_at)
            VALUES (
                " . db_escape($data['article_id']) . ",
                " . db_escape($data['session_id'] ?? null) . ",
                " . db_escape($data['user_id'] ?? null) . ",
                " . db_escape($data['rating']) . ",
                " . db_escape($data['comment'] ?? null) . ",
                NOW()
            )";

        db_query($sql, "Could not create feedback");
        $feedback = new self($data);
        $feedback->id = db_insert_id();

        return $feedback;
    }

    public static function userFeedback(int $article_id, ?string $session_id = null, ?int $user_id = null): ?self
    {
        global $db;
        include_once '../../../includes/db.inc';

        $conditions[] = "article_id = " . db_escape($article_id);

        if ($user_id) {
            $conditions[] = "user_id = " . db_escape($user_id);
        } elseif ($session_id) {
            $conditions[] = "session_id = " . db_escape($session_id);
        } else {
            return null;
        }

        $sql = "SELECT * FROM " . TB_PREF . "kb_feedback 
            WHERE " . implode(' AND ', $conditions) . "
            ORDER BY created_at DESC 
            LIMIT 1";

        $result = db_query($sql, "Could not get user feedback");
        $row = db_fetch_assoc($result);

        return $row ? new self($row) : null;
    }
}