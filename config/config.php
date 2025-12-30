<?php
/**
 * Zettelkasten Configuration File
 *
 * ⚠️ SECURITY NOTES:
 * - This file loads sensitive data from environment variables.
 * - Ensure `.env` is in `.gitignore` and has strict permissions (chmod 600).
 */

// Session Configuration (fallback to secure defaults)
define('SESSION_LIFETIME', getenv('SESSION_LIFETIME') ?: 60 * 60 * 24 * 30); // 30 days
define('SESSION_TIMEOUT', getenv('SESSION_TIMEOUT') ?: 60 * 60 * 2);         // 2 hours

// Authentication Credentials (from .env)
define('USERNAME', getenv('ZETTEL_USERNAME'));
define('PASSWORD_HASH', password_hash(getenv('ZETTEL_PASSWORD'), PASSWORD_DEFAULT));

// Security Settings (fallback to secure defaults)
define('MAX_LOGIN_ATTEMPTS', getenv('MAX_LOGIN_ATTEMPTS') ?: 5);
define('LOGIN_LOCKOUT_TIME', getenv('LOGIN_LOCKOUT_TIME') ?: 900); // 15 minutes

// Application Settings (fallback to secure defaults)
define('ZETTELS_DIR', getenv('ZETTELS_DIR') ?: '/var/www/html/zettels');
define('ZETTELS_PER_PAGE', getenv('ZETTELS_PER_PAGE') ?: 10);
define('RELATED_ZETTELS_LIMIT', getenv('RELATED_ZETTELS_LIMIT') ?: 5);

// Debug Mode (force false unless explicitly set to 'true')
define('APP_DEBUG', getenv('APP_DEBUG') === 'true');

// CSRF Token Settings (fallback to 6 hours)
define('CSRF_TOKEN_LIFETIME', getenv('CSRF_TOKEN_LIFETIME') ?: 21600);
?>
