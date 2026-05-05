<?php

class ConexionBBDD {
    private static ?PDO $pdo = null;
    
    public static function obtener(): PDO {
        if (self::$pdo !== null) {
            return self::$pdo;
        }
        
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NOMBRE . ';charset=' . DB_CHARSET;

        self::$pdo = new PDO($dsn, DB_USUARIO, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$pdo;
    }
}