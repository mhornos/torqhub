<?php

return [
    'GET' => [
        '/' => [
            'accion' => 'InicioControlador@index',
        ],
        '/login' => [
            'accion' => 'AuthControlador@login',
        ],
        '/registro' => [
            'accion' => 'AuthControlador@registro',
        ],
        '/logout' => [
            'accion' => 'AuthControlador@logout',
        ],
        '/garaje' => [
            'accion' => 'GarajeControlador@index',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/nuevo' => [
            'accion' => 'GarajeControlador@nuevo',
            'middleware' => 'AuthMiddleware',
        ],
        '/admin' => [
            'accion' => 'AdminControlador@index',
            'middleware' => 'AdminMiddleware',
        ],
    ],
    'POST' => [
        '/login' => [
            'accion' => 'AuthControlador@login_post',
        ],
        '/registro' => [
            'accion' => 'AuthControlador@registro_post',
        ],
        '/garaje/nuevo' => [
            'accion' => 'GarajeControlador@nuevo_post',
            'middleware' => 'AuthMiddleware',
        ],
    ],
];
    
?>