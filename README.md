# TorqHub

TorqHub es una plataforma web social para aficionados al motor desarrollada desde cero con PHP y arquitectura MVC propia.

El proyecto permite centralizar la gestión de vehículos personales, la participación en una comunidad de usuarios y el acceso a herramientas de ayuda relacionadas con el mantenimiento y diagnóstico mecánico.

TorqHub ha sido desarrollado como proyecto final del ciclo de Desarrollo de Aplicaciones Web (DAW), aplicando conceptos de backend, frontend, bases de datos, seguridad, arquitectura MVC, consumo de APIs, multilenguaje y administración de contenidos.

---

## Objetivo del proyecto

El objetivo principal de TorqHub es crear una plataforma funcional, escalable y defendible técnicamente, orientada a usuarios aficionados al motor.

La aplicación permite a los usuarios:

- gestionar un garaje virtual con sus vehículos
- consultar, guardar y exportar información relacionada con cada vehículo
- consultar datos de vehículos mediante una API VIN externa con sistema de caché
- acceder a un sistema de diagnóstico mecánico asistido mediante inteligencia artificial basada en reglas
- participar en una comunidad mediante publicaciones, comentarios y reacciones
- gestionar su perfil público y privado
- utilizar la aplicación en castellano y catalán
- acceder a un panel de administración con control de usuarios, publicaciones y base de conocimiento IA

Además, el proyecto está diseñado para poder ampliarse con:

- clubs de coches según marca, modelo o estilo
- chats en tiempo real de tipo global, privado o por club
- notificaciones internas dentro de la plataforma
- quedadas, rutas y eventos entre usuarios
- integración de APIs externas relacionadas con ubicación, clima u otros servicios del motor

---

## Funcionalidades implementadas

### Autenticación y usuarios

- Registro de usuarios
- Inicio y cierre de sesión
- Recuperación de contraseña mediante correo electrónico
- Cambio de contraseña desde el perfil
- Validación de contraseñas
- Gestión de sesiones segura
- Control de usuarios activos e inactivos
- Roles de usuario y administrador

---

### Garaje virtual

- Creación, edición y eliminación de vehículos
- Visualización detallada de cada vehículo
- Subida de imagen del vehículo
- Historial de mantenimientos
- Filtros por tipo, fecha, kilómetros y coste
- Ordenación de mantenimientos
- Paginación mediante AJAX
- Resumen económico y técnico del vehículo
- Exportación CSV del historial
- Integración con API VIN externa
- Caché local para consultas VIN

---

### Comunidad

- Creación, edición y eliminación de publicaciones
- Publicaciones con texto e imagen
- Listado de publicaciones
- Vista detalle de publicación
- Comentarios
- Respuestas a comentarios
- Likes mediante AJAX
- Buscador de publicaciones
- Ordenación
- Paginación
- Enlaces a perfiles públicos de usuarios

---

### Perfiles

- Perfil público de usuario
- Visualización de publicaciones del usuario
- Edición del perfil propio
- Cambio de nombre de usuario
- Cambio de correo electrónico
- Cambio de contraseña
- Foto de perfil
- Acceso al perfil desde la barra de navegación

---

### Diagnóstico mecánico asistido

TorqHub incluye un sistema de diagnóstico asistido basado en reglas y recomendación.

Este módulo no depende de APIs externas ni de servicios de pago. La lógica está desarrollada dentro del propio proyecto mediante un sistema experto.

El sistema permite:

- escribir síntomas mecánicos en formato chat
- analizar el texto introducido por el usuario
- normalizar el texto
- detectar palabras clave
- puntuar posibles causas
- devolver resultados ordenados por confianza
- mostrar recomendaciones básicas al usuario
- reiniciar la conversación
- funcionar mediante AJAX sin recargar la página

Actualmente, la base de conocimiento de IA se ha migrado de arrays internos a base de datos mediante las tablas:

- `ia_causas`
- `ia_keywords`

El motor mantiene un sistema de fallback interno para que el diagnóstico siga funcionando aunque la base de datos no esté disponible.

---

### Panel de administración

El proyecto incluye un panel de administración básico protegido por middleware de administrador.

Desde el panel se puede:

- acceder a un dashboard administrativo
- listar usuarios
- cambiar roles de usuario
- activar o desactivar usuarios
- impedir que un administrador se quite a sí mismo el rol admin
- impedir que un administrador se desactive a sí mismo
- listar publicaciones de la comunidad
- eliminar publicaciones con protección CSRF
- consultar la base de conocimiento IA
- ver causas, recomendaciones, estado y keywords del sistema experto

---

### Multilenguaje

La aplicación incluye soporte para:

- Castellano
- Catalán

El sistema de idioma se aplica en vistas principales, formularios, mensajes y textos relevantes de la interfaz.

---

### Seguridad

TorqHub aplica medidas de seguridad propias de una aplicación web desarrollada en PHP:

- Uso de PDO para evitar inyección SQL
- Consultas preparadas
- Protección CSRF en formularios sensibles
- Escapado de salida para prevenir XSS
- Validación de formularios
- Middleware de autenticación
- Middleware de administración
- Control de permisos por usuario
- Regeneración de sesión
- Configuración segura de cookies de sesión
- Control de usuarios activos
- Mensajes de error limpios para el usuario
- Logs técnicos mediante `error_log`

---

## Tecnologías utilizadas

### Backend

- PHP
- MySQL / MariaDB
- PDO
- Arquitectura MVC propia
- Sesiones
- Middlewares propios
- PHPMailer
- Consumo de APIs REST

### Frontend

- HTML5
- CSS3
- JavaScript ES6
- Fetch API
- AJAX
- Diseño responsive

### Herramientas

- XAMPP
- Apache
- phpMyAdmin
- Git
- GitHub
- Visual Studio Code

---

## Arquitectura del proyecto

TorqHub utiliza una arquitectura MVC propia, separando responsabilidades entre rutas, controladores, modelos, servicios, vistas y middlewares.

Estructura principal del proyecto:

```text
/
├── aplicacion/
│   ├── configuracion/
│   ├── controladores/
│   ├── middlewares/
│   ├── modelos/
│   ├── servicios/
│   └── vistas/
├── almacenamiento/
│   ├── cache/
│   ├── logs/
│   └── sql/
├── public/
│   ├── css/
│   ├── img/
│   ├── js/
│   └── uploads/
├── rutas/
├── index.php
├── env.php
├── .htaccess
├── .gitignore
└── README.md
