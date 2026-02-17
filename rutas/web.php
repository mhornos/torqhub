<?php

return [
    "GET" => [
        "/" => "InicioControlador@index",
        "/login" => "AuthControlador@login",
        "/registro" => "AuthControlador@registro",
        "/logout" => "AuthControlador@logout",
    ],
    "POST" => [
        "/login" => "AuthControlador@login_post",
        "/registro" => "AuthControlador@registro_post",
    ],
];
    
?>