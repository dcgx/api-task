<img src="https://avatars.githubusercontent.com/u/26632876?v=4" width="30%" alt="Logo of the project" align="right">

# 🚀 KiiboTask
API Rest para la gestión de tareas. 

## Installing / Getting started

Instalación de dependencias

```shell
composer install
```

Configuración de variables de entorno

```shell
cp .env.example .env
```

Generación de clave de aplicación

```shell
php artisan key:generate
```

Creación de base de datos

```shell
php artisan migrate
```

Creación de datos de prueba

```shell
php artisan db:seed
```

## Developing

### Technologies / Built With
Enumera las bibliotecas principales, los frameworks utilizados, incluidas las versiones (React, Angular, etc.)
- ✨ Laravel/PHP
- 📊 MySQL
- ✅ Pest

### Dependencies / Prerequisites
Qué se necesita para configurar el entorno de desarrollo. Por ejemplo, dependencias globales o cualquier otra herramienta. incluir enlaces de descarga.
- 📦 composer
- 🐳 Docker
- 🐘 PHP 8.2.10


### Setting up Dev


```shell
git clone https://github.com/dcgx/kiibo-task.git
cd kiibo-task/
code .
```


### Deploying / Publishing
El servicio está desplegado en [Fly.io](https://fly.io/). Para desplegarlo, sigue los siguientes pasos:

```shell
fly deploy
```

Y de nuevo tendrías que contar qué hace el código anterior.

## Tests
Describe y muestra cómo ejecutar las pruebas con ejemplos de código.
Cuenta qué pruebas son y por qué son estas pruebas y no otras.

```shell
php artisan test
```

