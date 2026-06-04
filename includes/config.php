<?php
// includes/config.php

declare(strict_types=1);

/**
 * Error reporting (disable in production later)
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * -----------------------
 * DATABASE CONFIGURATION
 * -----------------------
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'arise_rentals');

/**
 * -----------------------
 * SITE CONFIGURATION
 * -----------------------
 */
define('SITE_NAME', 'Arise Car Rentals');
define('SITE_EMAIL', 'info@ariserenals.ae');
define('SITE_PHONE', '+971 4 123 4567');

/**
 * -----------------------
 * BASE URL DETECTION
 * -----------------------
 */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? 'https://'
    : 'http://';

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

if ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '\\') {
    $scriptDir = '';
}

define('BASE_URL', rtrim($protocol . $host . $scriptDir, '/'));
define('SITE_URL', BASE_URL);

/**
 * -----------------------
 * SESSION START
 * -----------------------
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * -----------------------
 * DATABASE CONNECTION
 * -----------------------
 */
/*function db(): mysqli
{
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        if (!$conn->set_charset("utf8mb4")) {
            die("Charset error: " . $conn->error);
        }
    }

    return $conn;
}*/

/**
 * -----------------------
 * URL HELPER
 * -----------------------
 */
function url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * -----------------------
 * REDIRECT HELPER
 * -----------------------
 */
function redirect(string $path = ''): never
{
    header('Location: ' . url($path));
    exit;
}

/**
 * -----------------------
 * PAGE HELPERS
 * -----------------------
 */
function currentPage(): string
{
    return basename($_SERVER['PHP_SELF'] ?? '');
}

function isActivePage(string $page): string
{
    return currentPage() === $page ? 'nav-active' : '';
}