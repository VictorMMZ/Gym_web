# Gym Web

Aplicación web para la gestión básica de un gimnasio. El proyecto permite consultar las clases disponibles, registrarse con un plan, iniciar sesión e inscribirse en actividades.

Es un proyecto PHP tradicional, por lo que no necesita un servidor Node. Apache sirve las páginas de `public_html` y MySQL guarda los usuarios, planes, clases e inscripciones.

## Demo

Actualmente no hay una demo pública configurada. Se puede probar en local con XAMPP:

```text
http://localhost/Gym_Web/public_html/
```

### Credenciales de prueba

No hay un usuario fijo incluido en el proyecto. Para probar el acceso:

1. Ejecuta la instalación de la base de datos.
2. Registra un usuario desde `signup_plan.php`.
3. Inicia sesión desde `login.php`.

La contraseña debe tener al menos 8 caracteres, una mayúscula y un carácter especial. El rol de usuario se asigna durante el registro.

## Funcionalidades

### Parte pública

- Página de inicio con información del gimnasio.
- Horario de apertura y cierre.
- Listado de planes disponibles.
- Formulario de contacto.
- Registro e inicio de sesión.

### Zona de usuario

- Dashboard personal.
- Consulta de clases.
- Inscripción y cancelación de clases.
- Consulta de las clases inscritas.
- Visualización del perfil.

### Zona de administración

- Dashboard con información general.
- Gestión de usuarios.
- Gestión de clases y horarios.
- Consulta de mensajes recibidos.
- Gestión básica de facturación.

## Tecnologías

- PHP 8+
- MySQL
- PDO para las consultas a la base de datos
- HTML, CSS y JavaScript
- Bootstrap
- Font Awesome y AOS

## Requisitos

- XAMPP con Apache y MySQL.
- PHP 8 o superior.
- Una base de datos MySQL vacía.

## Instalación local

1. Copia la carpeta dentro de `C:\xampp2.0\htdocs`.
2. Crea una base de datos y configura sus datos en `config/db.php`.
3. Ejecuta `config/setup.php` una vez para crear las tablas y algunos datos iniciales.
4. Enciende Apache y MySQL desde XAMPP.
5. Abre la aplicación:

```text
http://localhost/Gym_Web/public_html/
```

> No subas el archivo `.env` ni las credenciales de la base de datos a un repositorio público.

## Estructura del proyecto

```text
app/
├── auth.php              Autenticación y sesiones
├── ClaseHelper.php       Operaciones relacionadas con clases
├── HorarioHelper.php     Lectura del horario del gimnasio
└── UsuariosHelper.php    Operaciones relacionadas con usuarios

config/
├── db.php                Conexión PDO a MySQL
└── setup.php             Creación de tablas y datos iniciales

public_html/
├── index.php             Página principal
├── login.php             Inicio de sesión
├── registro.php          Registro de usuarios
├── files_usuario/        Páginas de usuarios
└── files_admin/          Páginas de administración
```

## Base de datos

El script de configuración crea tablas para:

- Gimnasio y planes.
- Usuarios y roles.
- Clases e inscripciones.
- Facturación.
- Mensajes de contacto.

**Proyecto desarrollado como parte de mi portfolio de desarrollo web. Victor Manuel Marrero Zayas**