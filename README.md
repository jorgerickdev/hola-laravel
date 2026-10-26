# Hola-Laravel

Tutorial de Una tecnología/Framework    LARAVEL

El objetivo del proyecto es demostrar la creación de una infraestructura contenerizada capaz de ejecutar una aplicación Laravel en desarrollo local y posteriormente desplegarla en Internet mediante Render.

---

## Tecnologías utilizadas

- Docker
- Docker Compose
- PHP 8.4
- Composer
- Laravel 13
- Git
- GitHub
- Render

---

## Arquitectura

```text
Equipo Local
↓
Docker
↓
Laravel
↓
Git
↓
GitHub
↓
Render
↓
Internet
```

---

## Estructura del proyecto

```text
Hola-Laravel
│
├── README.md
├── docker-compose.yml
│
└── src
    ├── Dockerfile
    ├── .dockerignore
    ├── app
    ├── bootstrap
    ├── config
    ├── database
    ├── public
    ├── resources
    ├── routes
    ├── storage
    ├── artisan
    ├── composer.json
    └── composer.lock
```

---

## Ejecución local

Crear el contenedor:

```bash
docker compose up -d
```

Verificar:

```bash
docker ps
```

Entrar al contenedor:

```bash
docker exec -it hola-laravel bash
```

Iniciar Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Abrir:

```text
http://localhost:8000
```

---

## Verificaciones

Información del proyecto:

```bash
php artisan about
```

Rutas registradas:

```bash
php artisan route:list
```

Entorno utilizado:

```bash
php artisan env
```

---

## Control de versiones

Inicializar Git:

```bash
git init
```

Agregar archivos:

```bash
git add .
```

Verificar SQLite:

```bash
git ls-files | grep sqlite
```

Si es necesario:

```bash
git add -f src/database/database.sqlite
```

Crear commit:

```bash
git commit -m "Proyecto completo Hola-Laravel"
```

Publicar:

```bash
git push -u origin main
```

---

## Despliegue

El proyecto se encuentra preparado para desplegarse mediante Render utilizando Docker.

Configuración utilizada:

```text
Dockerfile Path:
src/Dockerfile
```

```text
Docker Build Context Directory:
src
```

Variables de entorno:

```text
APP_KEY
APP_DEBUG=true
```

---

## Resultado

✅ Docker funcionando

✅ Laravel funcionando

✅ GitHub funcionando

✅ Render funcionando

✅ SQLite incluida correctamente

✅ APP_KEY configurada

✅ Aplicación desplegada en Internet

---

## URL pública

```text
https://hola-laravel.onrender.com
```

---

## Autores

@jorgerickdev
@vic
@borbolla
@Jperaltaa

Proyecto desarrollado como práctica 2 "Tutorial de un Framework" de la Materia de Desarrollo de Aplicacionesen Red
de La Ingenieria en sistemas de la UNIR Mèxico 
Tecnologías PHP, Composer, Laravel, Docker, Laravel, GitHub y Render.
