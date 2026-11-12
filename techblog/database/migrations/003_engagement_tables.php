<?php
/** Comments, the media library and contact form messages. */
return [
    "CREATE TABLE IF NOT EXISTS comments (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        post_id INT UNSIGNED NOT NULL,
        user_id INT UNSIGNED NOT NULL,
        parent_id INT UNSIGNED NULL,
        body TEXT NOT NULL,
        status ENUM('pending','approved','spam','trash') NOT NULL DEFAULT 'pending',
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        KEY idx_comments_post (post_id, status, created_at),
        KEY idx_comments_status (status, created_at),
        CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
        CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
        CONSTRAINT fk_comments_parent FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS media (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NULL,
        original_name VARCHAR(200) NOT NULL,
        path VARCHAR(300) NOT NULL,
        mime VARCHAR(60) NOT NULL,
        width INT UNSIGNED NOT NULL DEFAULT 0,
        height INT UNSIGNED NOT NULL DEFAULT 0,
        size INT UNSIGNED NOT NULL DEFAULT 0,
        alt VARCHAR(300) NOT NULL,
        variants TEXT NULL,
        created_at DATETIME NOT NULL,
        KEY idx_media_created (created_at),
        CONSTRAINT fk_media_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        KEY idx_contact_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];
