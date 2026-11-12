<?php
/** Users, settings, rate limits and the audit log. */
return [
    "CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        firebase_uid VARCHAR(128) NOT NULL,
        email VARCHAR(190) NOT NULL,
        name VARCHAR(120) NOT NULL DEFAULT '',
        avatar VARCHAR(500) NOT NULL DEFAULT '',
        role ENUM('user','editor','admin') NOT NULL DEFAULT 'user',
        is_banned TINYINT(1) NOT NULL DEFAULT 0,
        email_verified TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        last_login_at DATETIME NULL,
        UNIQUE KEY uq_users_uid (firebase_uid),
        KEY idx_users_email (email),
        KEY idx_users_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS settings (
        `key` VARCHAR(100) NOT NULL PRIMARY KEY,
        `value` MEDIUMTEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS rate_limits (
        k CHAR(40) NOT NULL PRIMARY KEY,
        hits INT UNSIGNED NOT NULL DEFAULT 0,
        expires_at INT UNSIGNED NOT NULL,
        KEY idx_rate_expires (expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS audit_log (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NULL,
        action VARCHAR(60) NOT NULL,
        entity VARCHAR(60) NOT NULL DEFAULT '',
        entity_id VARCHAR(120) NULL,
        details TEXT NULL,
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        KEY idx_audit_created (created_at),
        KEY idx_audit_user (user_id),
        CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];
