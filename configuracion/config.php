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

?>