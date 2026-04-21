<?php

//definición de rutas para la aplicación, cada ruta tiene una acción asociada y opcionalmente un middleware

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
        '/garaje/eliminar' => [
            'accion' => 'GarajeControlador@eliminar',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/editar' => [
            'accion' => 'GarajeControlador@editar',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/ver' => [
            'accion' => 'GarajeControlador@ver',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/nuevo' => [
            'accion' => 'GarajeControlador@mantenimiento_nuevo',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/editar' => [
            'accion' => 'GarajeControlador@mantenimiento_editar',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/filtrar' => [
            'accion' => 'GarajeControlador@mantenimientos_filtrar',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/exportar-csv' => [
            'accion' => 'GarajeControlador@mantenimientos_exportar_csv',
            'middleware' => 'AuthMiddleware',
        ],
        '/comunidad' => [
            'accion' => 'ComunidadControlador@index',
            'middleware' => 'AuthMiddleware',
        ],
        '/comunidad/nueva' => [
            'accion' => 'ComunidadControlador@nueva',
            'middleware' => 'AuthMiddleware',
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
        '/garaje/eliminar' => [
            'accion' => 'GarajeControlador@eliminar_post',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/editar' => [
            'accion' => 'GarajeControlador@editar_post',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/nuevo' => [
            'accion' => 'GarajeControlador@mantenimiento_nuevo_post',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/editar' => [
            'accion' => 'GarajeControlador@mantenimiento_editar_post',
            'middleware' => 'AuthMiddleware',
        ],
        '/garaje/mantenimientos/eliminar' => [
            'accion' => 'GarajeControlador@mantenimiento_eliminar_post',
            'middleware' => 'AuthMiddleware',
        ],
        '/comunidad/nueva' => [
            'accion' => 'ComunidadControlador@nueva_post',
            'middleware' => 'AuthMiddleware',
        ],
    ],
];
    
?>