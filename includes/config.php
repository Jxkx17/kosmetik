<?php
/**
 * Configuration. Les valeurs sensibles sont lues depuis les variables
 * d'environnement ; les valeurs par défaut conviennent au développement local.
 * Pour surcharger sans toucher au code : créez includes/config.local.php
 * qui retourne un tableau de clés à remplacer.
 */
declare(strict_types=1);

$config = [
    'app_name'    => 'KOSMETIK',
    'currency'    => 'FCFA',
    'debug'       => getenv('APP_DEBUG') === '1',
    'db_host'     => getenv('DB_HOST') ?: 'localhost',
    'db_name'     => getenv('DB_NAME') ?: 'kosmetik',
    'db_user'     => getenv('DB_USER') ?: 'root',
    'db_pass'     => getenv('DB_PASS') ?: '',
    'smtp_host'   => getenv('SMTP_HOST') ?: 'localhost',
    'smtp_port'   => (int) (getenv('SMTP_PORT') ?: 1025),
    'mail_from'   => getenv('MAIL_FROM') ?: 'no-reply@kosmetik.com',
    'contact_email' => 'support@kosmetik.com',
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_merge($config, (array) require $local);
}

return $config;
