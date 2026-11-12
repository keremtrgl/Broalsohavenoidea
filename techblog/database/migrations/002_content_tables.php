<?php
/** Categories, tags, posts, revisions and daily view counters. */
return [
    "CREATE TABLE IF NOT EXISTS categories (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(120) NOT NULL,
        description VARCHAR(300) NOT NULL DEFAULT '',
        icon VARCHAR(30) NOT NULL DEFAULT 'chip',
        sort_order INT NOT NULL DEFAULT 0,
        UNIQUE KEY uq_categories_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS tags (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(120) NOT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        UNIQUE KEY uq_tags_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS posts (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        author_id INT UNSIGNED NULL,
        category_id INT UNSIGNED NULL,
        title VARCHAR(200) NOT NULL,
        slug VARCHAR(160) NOT NULL,
        excerpt VARCHAR(400) NOT NULL DEFAULT '',
        content_json LONGTEXT NOT NULL,
        content_text LONGTEXT NOT NULL,
        cover_image VARCHAR(300) NOT NULL DEFAULT '',
        cover_alt VARCHAR(300) NOT NULL DEFAULT '',
        status ENUM('draft','published') NOT NULL DEFAULT 'draft',
        published_at DATETIME NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        reading_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 1,
        word_count INT UNSIGNED NOT NULL DEFAULT 0,
        meta_title VARCHAR(200) NOT NULL DEFAULT '',
        meta_description VARCHAR(320) NOT NULL DEFAULT '',
        og_image VARCHAR(300) NOT NULL DEFAULT '',
        canonical_url VARCHAR(300) NOT NULL DEFAULT '',
        is_featured TINYINT(1) NOT NULL DEFAULT 0,
        series VARCHAR(100) NOT NULL DEFAULT '',
        series_order SMALLINT NOT NULL DEFAULT 0,
        show_updated TINYINT(1) NOT NULL DEFAULT 0,
        views INT UNSIGNED NOT NULL DEFAULT 0,
        UNIQUE KEY uq_posts_slug (slug),
        KEY idx_posts_listing (status, published_at),
        KEY idx_posts_category (category_id),
        KEY idx_posts_series (series, series_order),
        FULLTEXT KEY ft_posts (title, excerpt, content_text),
        CONSTRAINT fk_posts_author FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE SET NULL,
        CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS post_tags (
        post_id INT UNSIGNED NOT NULL,
        tag_id INT UNSIGNED NOT NULL,
        PRIMARY KEY (post_id, tag_id),
        KEY idx_post_tags_tag (tag_id),
        CONSTRAINT fk_pt_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
        CONSTRAINT fk_pt_tag FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS post_revisions (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        post_id INT UNSIGNED NOT NULL,
        user_id INT UNSIGNED NULL,
        title VARCHAR(200) NOT NULL,
        content_json LONGTEXT NOT NULL,
        created_at DATETIME NOT NULL,
        KEY idx_rev_post (post_id, created_at),
        CONSTRAINT fk_rev_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
        CONSTRAINT fk_rev_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS post_views (
        post_id INT UNSIGNED NOT NULL,
        view_date DATE NOT NULL,
        views INT UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (post_id, view_date),
        KEY idx_views_date (view_date),
        CONSTRAINT fk_views_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];
