-- Seed: Sun Life ETF+ Portfolios knowledge-base article
-- Target: FrontAccounting KB tables (fa_kb_categories, fa_kb_articles)
-- Source of truth: src/Ksfraser/KnowledgeBase/Data/SunLifeEtfPlusPortfolios.php
--               and ~/.hermes/skills/ksf_stockmarket/references/sun_life_etf_plus.json
-- Run on the FA database host (local MySQL is not running on the dev box).
-- Idempotent: guarded with NOT EXISTS.

INSERT INTO `fa_kb_categories` (`name`, `description`, `display_order`)
SELECT 'Investment Products', 'Sun Life and other investment product families (ETF+, seg funds).', 10
WHERE NOT EXISTS (SELECT 1 FROM `fa_kb_categories` WHERE `name` = 'Investment Products');

SET @cat_id = (SELECT `id` FROM `fa_kb_categories` WHERE `name` = 'Investment Products' LIMIT 1);

INSERT INTO `fa_kb_articles`
    (`title`, `content`, `category_id`, `tags`, `status`, `author_id`)
SELECT
    'Sun Life ETF+ Portfolios - Fund Family Reference',
    'Five actively managed mutual fund trusts (also available as segregated funds) from SLGI Asset Management Inc., effective 2025-12-08. They combine core mutual funds with passive ETFs (Vanguard, iShares, State Street) plus targeted allocations to gold, private fixed income, and a proprietary U.S. sector rotation strategy. Management fees range from 0.375% (Series F) to 1.450% (Series A). Full fund facts, asset mixes, and fund codes: see doc/SunLifeEtfPlusPortfolios.md (ksfraser/ksf-knowledgebase) and the Sun Life ETF+ Portfolios advisor guide. NOTE: listed returns are predecessor Tactical ETF Portfolios Series F figures, not ETF+ returns.',
    @cat_id,
    'sun life,etf+,seg fund,portfolio,slgi',
    'published',
    NULL
WHERE NOT EXISTS (
    SELECT 1 FROM `fa_kb_articles` WHERE `title` = 'Sun Life ETF+ Portfolios - Fund Family Reference'
);
