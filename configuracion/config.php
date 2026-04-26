<?php

// ruta base
define('BASE_URL', '/torqhub');

// bd config
$env = require __DIR__ . '/../env.php';
define('DB_HOST', $env['db_host']);
define('DB_NOMBRE', $env['db_nombre']);
define('DB_USUARIO', $env['db_usuario']);
define('DB_PASSWORD', $env['db_password']);
define('DB_CHARSET', $env['db_charset']);

// configuración smtp
define('SMTP_HOST', $env['smtp_host']);
define('SMTP_USUARIO', $env['smtp_usuario']);
define('SMTP_PASSWORD', $env['smtp_password']);
define('SMTP_PUERTO', $env['smtp_puerto']);
define('SMTP_REMITENTE', $env['smtp_remitente']);
define('SMTP_NOMBRE_REMITENTE', $env['smtp_nombre_remitente']);

?>