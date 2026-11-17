<?php
/**
 * Point d'entrée commun : configuration, session sécurisée, en-têtes HTTP,
 * connexion BD et fonctions utilitaires (échappement, CSRF, auth, flash).
 */
declare(strict_types=1);

$CONFIG = require __DIR__ . '/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $CONFIG['debug'] ? '1' : '0');
ini_set('log_errors', '1');

// ---------- En-têtes de sécurité ----------
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; script-src 'self'; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");

// ---------- Session ----------
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('KOSMETIKSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ---------- Base de données ----------
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli
{
    static $db = null;
    global $CONFIG;
    if ($db === null) {
        try {
            $db = new mysqli($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass'], $CONFIG['db_name']);
            $db->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            error_log('DB connection failed: ' . $e->getMessage());
            http_response_code(503);
            exit('Service momentanément indisponible. Veuillez réessayer plus tard.');
        }
    }
    return $db;
}

/** Exécute une requête préparée et retourne le statement. */
function db_query(string $sql, string $types = '', array $params = []): mysqli_stmt
{
    $stmt = db()->prepare($sql);
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}

function db_all(string $sql, string $types = '', array $params = []): array
{
    return db_query($sql, $types, $params)->get_result()->fetch_all(MYSQLI_ASSOC);
}

function db_one(string $sql, string $types = '', array $params = []): ?array
{
    $row = db_query($sql, $types, $params)->get_result()->fetch_assoc();
    return $row ?: null;
}

// Erreurs non gérées : journaliser, afficher un message neutre.
set_exception_handler(function (Throwable $e) use ($CONFIG) {
    error_log((string) $e);
    http_response_code(500);
    echo $CONFIG['debug'] ? '<pre>' . e((string) $e) . '</pre>' : 'Une erreur est survenue. Veuillez réessayer.';
});

// ---------- Utilitaires ----------
function e(mixed $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function money(float|string $amount): string
{
    global $CONFIG;
    return number_format((float) $amount, 0, ',', ' ') . ' ' . $CONFIG['currency'];
}

function redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

function take_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/** Conserve les saisies d'un formulaire pour le réafficher après une erreur. */
function old(string $key, string $default = ''): string
{
    return (string) ($_SESSION['old'][$key] ?? $default);
}

// ---------- CSRF ----------
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Exige une requête POST avec un jeton CSRF valide. */
function require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Méthode non autorisée.');
    }
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(400);
        flash('error', 'Votre session a expiré. Veuillez réessayer.');
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
    }
}

// ---------- Authentification ----------
function current_user(): ?array
{
    return isset($_SESSION['user_id']) ? [
        'id'    => (int) $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'fname' => $_SESSION['fname'],
        'type'  => $_SESSION['type'],
    ] : null;
}

function is_admin(): bool
{
    return ($_SESSION['type'] ?? '') === 'admin';
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        flash('info', 'Connectez-vous pour accéder à cette page.');
        redirect('login.php');
    }
    return $u;
}

function require_admin(): array
{
    $u = require_login();
    if (!is_admin()) {
        http_response_code(403);
        redirect('index.php');
    }
    return $u;
}

function login_user(array $user): void
{
    session_regenerate_id(true); // anti fixation de session
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['fname']   = $user['fname'];
    $_SESSION['type']    = $user['type'];
    unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
}

function cart(): array
{
    return is_array($_SESSION['cart'] ?? null) ? $_SESSION['cart'] : [];
}

function cart_count(): int
{
    return array_sum(cart());
}

function post_str(string $key, int $max = 255): string
{
    $v = $_POST[$key] ?? '';
    return is_string($v) ? mb_substr(trim($v), 0, $max) : '';
}
