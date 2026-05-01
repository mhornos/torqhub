<?php

return [
    // navabar
    'navbar.inicio' => 'Inici',
    'navbar.login' => 'Iniciar sessió',
    'navbar.registro' => 'Registrar-se',
    'navbar.garaje' => 'El meu garatge',
    'navbar.comunidad' => 'Comunitat',
    'navbar.diagnostico' => 'Diagnòstic',
    'navbar.perfil' => 'El meu perfil',
    'navbar.admin' => 'Panell d’administració',
    'navbar.logout' => 'Tancar sessió',

    'navbar.idioma_es' => 'ES',
    'navbar.idioma_ca' => 'CA',
    'navbar.cambiar_idioma' => 'Canviar idioma',

    // auth - login
    'auth.login.titulo_pagina' => 'Iniciar sessió',
    'auth.login.titulo' => 'Iniciar sessió',
    'auth.login.email' => 'Correu electrònic',
    'auth.login.password' => 'Contrasenya',
    'auth.login.boton' => 'Entrar',
    'auth.login.password_olvidada' => 'He oblidat la contrasenya',

    // auth - registro
    'auth.registro.titulo_pagina' => 'Registrar-se',
    'auth.registro.titulo' => 'Registrar-se',
    'auth.registro.nombre' => 'Nom d’usuari',
    'auth.registro.nombre_ayuda' => 'Només lletres minúscules, números, punts i guions baixos, sense espais, sense punts consecutius i sense acabar en punt',
    'auth.registro.email' => 'Correu electrònic',
    'auth.registro.password' => 'Contrasenya',
    'auth.registro.password_repetida' => 'Repetir contrasenya',
    'auth.registro.password_ayuda' => 'Mínim 8 caràcters, una majúscula, una minúscula i un número',
    'auth.registro.error_password_repetida' => 'Les contrasenyes no coincideixen',
    'auth.registro.boton' => 'Crear compte',

    // auth - recuperación
    'auth.password_olvidada.titulo_pagina' => 'Recuperar contrasenya',
    'auth.password_olvidada.titulo' => 'Recuperar contrasenya',
    'auth.password_olvidada.email' => 'Correu electrònic',
    'auth.password_olvidada.boton' => 'Enviar enllaç de recuperació',
    'auth.password_olvidada.volver_login' => 'Tornar a iniciar sessió',

    // auth - restablecer contraseña
    'auth.password_restablecer.titulo_pagina' => 'Restablir contrasenya',
    'auth.password_restablecer.titulo' => 'Restablir contrasenya',
    'auth.password_restablecer.password' => 'Nova contrasenya',
    'auth.password_restablecer.password_repetida' => 'Repetir contrasenya',
    'auth.password_restablecer.boton' => 'Guardar nova contrasenya',

    // auth - mensajes
    'auth.error.rellena_login' => 'Omple el correu electrònic i la contrasenya',
    'auth.error.servidor' => 'Error de servidor, intenta-ho més tard',
    'auth.error.credenciales' => 'Credencials incorrectes',
    'auth.ok.sesion_iniciada' => 'Sessió iniciada',
    'auth.ok.sesion_cerrada' => 'Sessió tancada correctament',

    'auth.error.registro_obligatorios' => 'Has d’omplir el nom d’usuari, el correu electrònic i la contrasenya',
    'auth.error.password_no_coincide' => 'Les contrasenyes no coincideixen',
    'auth.error.nombre_requisitos' => 'El nom d’usuari només pot contenir lletres minúscules, números, punts i guions baixos, sense espais, sense punts consecutius i sense acabar en punt',
    'auth.error.password_requisitos' => 'La contrasenya ha de tenir mínim 8 caràcters, una majúscula, una minúscula i un número',
    'auth.error.nombre_uso' => 'Aquest nom d’usuari ja està en ús',
    'auth.error.email_registrado' => 'Aquest correu electrònic ja està registrat',
    'auth.ok.registro_completado' => 'Registre completat, ara inicia sessió',
    'auth.error.nombre_email_uso' => 'El nom d’usuari o el correu electrònic ja estan en ús',
    'auth.error.registro_error' => 'S’ha produït un error en registrar el compte',

    'auth.error.email_no_valido' => 'El correu electrònic no és vàlid',
    'auth.ok.recuperacion_enviada' => 'Si el correu existeix, rebràs un enllaç per restablir la contrasenya',
    'auth.error.token_no_valido' => 'Token no vàlid',
    'auth.error.enlace_expirado' => 'L’enllaç ja no és vàlid o ha caducat',
    'auth.error.enlace_restablecer_no_valido' => 'L’enllaç per restablir la contrasenya no és vàlid',
    'auth.error.campos_obligatorios' => 'Tots els camps són obligatoris',
    'auth.error.password_minimos' => 'La contrasenya no compleix els requisits mínims',
    'auth.ok.password_restablecida' => 'Contrasenya restablerta correctament',

    // inici
    'inicio.titulo_pagina' => 'Inici',
    'inicio.titulo' => 'Inici',
    'inicio.saludo' => 'Hola',
    'inicio.bienvenida' => 'Benvingut a TorqHub',

    // garatge - llistat
    'garaje.index.titulo_pagina' => 'El meu garatge',
    'garaje.index.titulo' => 'El meu garatge',
    'garaje.index.anadir_vehiculo' => 'Afegir vehicle',
    'garaje.index.sin_vehiculos' => 'Encara no tens vehicles',
    'garaje.index.ver' => 'Veure',
    'garaje.index.editar' => 'Editar',
    'garaje.index.eliminar' => 'Eliminar',

    // garatge - formulari vehicle
    'garaje.form.nuevo.titulo_pagina' => 'Afegir vehicle',
    'garaje.form.nuevo.titulo' => 'Afegir vehicle',
    'garaje.form.editar.titulo_pagina' => 'Editar vehicle',
    'garaje.form.editar.titulo' => 'Editar vehicle',

    'garaje.form.error.cargar_editar' => 'No s’ha pogut carregar el vehicle per editar',

    'garaje.form.marca' => 'Marca',
    'garaje.form.modelo' => 'Model',
    'garaje.form.any' => 'Any',
    'garaje.form.vin_opcional' => 'VIN',
    'garaje.form.vin' => 'VIN',
    'garaje.form.consultar_vin' => 'Consultar VIN',
    'garaje.form.vin_ayuda_nuevo' => 'Introdueix un VIN real de 17 caràcters per autocompletar dades del vehicle',
    'garaje.form.vin_ayuda_editar' => 'Si consultes el VIN, s’intentaran autocompletar les dades disponibles',

    'garaje.form.carroceria' => 'Carrosseria',
    'garaje.form.tipo_combustible' => 'Tipus de combustible',
    'garaje.form.tipo_cambio' => 'Tipus de canvi',
    'garaje.form.selecciona_opcion' => 'Selecciona una opció',

    'garaje.form.potencia_cv' => 'Potència (CV)',
    'garaje.form.cilindrada_cm3' => 'Cilindrada (cm³)',

    'garaje.form.imagen' => 'Imatge del vehicle',
    'garaje.form.imagen_ayuda_nuevo' => 'Formats permesos: JPG, PNG o WEBP. Màxim 3 MB',
    'garaje.form.cambiar_imagen' => 'Canviar imatge del vehicle',
    'garaje.form.cambiar_imagen_ayuda' => 'Si puges una imatge nova, substituirà l’actual. Màxim 3 MB',
    'garaje.form.imagen_actual' => 'Imatge actual',
    'garaje.form.alt_imagen_actual' => 'Imatge actual del vehicle',

    'garaje.form.cancelar' => 'Cancel·lar',
    'garaje.form.guardar_vehiculo' => 'Guardar vehicle',
    'garaje.form.guardar_cambios' => 'Guardar canvis',
];
