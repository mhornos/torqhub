<?php

return [
    // nav
    'navbar.inicio' => 'Inicio',
    'navbar.login' => 'Iniciar sesión',
    'navbar.registro' => 'Registrarse',
    'navbar.garaje' => 'Mi garaje',
    'navbar.comunidad' => 'Comunidad',
    'navbar.diagnostico' => 'Diagnóstico',
    'navbar.perfil' => 'Mi perfil',
    'navbar.admin' => 'Panel de administración',
    'navbar.logout' => 'Cerrar sesión',

    'navbar.idioma_es' => 'ES',
    'navbar.idioma_ca' => 'CA',
    'navbar.cambiar_idioma' => 'Cambiar idioma',


    // auth - login
    'auth.login.titulo_pagina' => 'Iniciar sesión',
    'auth.login.titulo' => 'Iniciar sesión',
    'auth.login.email' => 'Correo electrónico',
    'auth.login.password' => 'Contraseña',
    'auth.login.boton' => 'Entrar',
    'auth.login.password_olvidada' => 'He olvidado la contraseña',

    // auth - registro
    'auth.registro.titulo_pagina' => 'Registrarse',
    'auth.registro.titulo' => 'Registrarse',
    'auth.registro.nombre' => 'Nombre de usuario',
    'auth.registro.nombre_ayuda' => 'Solo letras minúsculas, números, puntos y guiones bajos, sin espacios, sin puntos consecutivos y sin terminar en punto',
    'auth.registro.email' => 'Correo electrónico',
    'auth.registro.password' => 'Contraseña',
    'auth.registro.password_repetida' => 'Repetir contraseña',
    'auth.registro.password_ayuda' => 'Mínimo 8 caracteres, una mayúscula, una minúscula y un número',
    'auth.registro.error_password_repetida' => 'Las contraseñas no coinciden',
    'auth.registro.boton' => 'Crear cuenta',

    // auth - recuperación
    'auth.password_olvidada.titulo_pagina' => 'Recuperar contraseña',
    'auth.password_olvidada.titulo' => 'Recuperar contraseña',
    'auth.password_olvidada.email' => 'Correo electrónico',
    'auth.password_olvidada.boton' => 'Enviar enlace de recuperación',
    'auth.password_olvidada.volver_login' => 'Volver a iniciar sesión',

    // auth - restablecer contraseña
    'auth.password_restablecer.titulo_pagina' => 'Restablecer contraseña',
    'auth.password_restablecer.titulo' => 'Restablecer contraseña',
    'auth.password_restablecer.password' => 'Nueva contraseña',
    'auth.password_restablecer.password_repetida' => 'Repetir contraseña',
    'auth.password_restablecer.boton' => 'Guardar nueva contraseña',

    // auth - mensajes
    'auth.error.rellena_login' => 'Rellena correo electrónico y contraseña',
    'auth.error.servidor' => 'Error de servidor, inténtalo más tarde',
    'auth.error.credenciales' => 'Credenciales incorrectas',
    'auth.ok.sesion_iniciada' => 'Sesión iniciada',
    'auth.ok.sesion_cerrada' => 'Sesión cerrada correctamente',

    'auth.error.registro_obligatorios' => 'Debes rellenar nombre de usuario, correo electrónico y contraseña',
    'auth.error.password_no_coincide' => 'Las contraseñas no coinciden',
    'auth.error.nombre_requisitos' => 'El nombre de usuario solo puede contener letras minúsculas, números, puntos y guiones bajos, sin espacios, sin puntos consecutivos y sin terminar en punto',
    'auth.error.password_requisitos' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número',
    'auth.error.nombre_uso' => 'Ese nombre de usuario ya está en uso',
    'auth.error.email_registrado' => 'Ese correo electrónico ya está registrado',
    'auth.ok.registro_completado' => 'Registro completado, ahora inicia sesión',
    'auth.error.nombre_email_uso' => 'El nombre de usuario o el correo electrónico ya están en uso',
    'auth.error.registro_error' => 'Se produjo un error al registrar la cuenta',

    'auth.error.email_no_valido' => 'El correo electrónico no es válido',
    'auth.ok.recuperacion_enviada' => 'Si el correo existe, recibirás un enlace para restablecer la contraseña',
    'auth.error.token_no_valido' => 'Token no válido',
    'auth.error.enlace_expirado' => 'El enlace ya no es válido o ha expirado',
    'auth.error.enlace_restablecer_no_valido' => 'El enlace para restablecer la contraseña no es válido',
    'auth.error.campos_obligatorios' => 'Todos los campos son obligatorios',
    'auth.error.password_minimos' => 'La contraseña no cumple los requisitos mínimos',
    'auth.ok.password_restablecida' => 'Contraseña restablecida correctamente',
];
