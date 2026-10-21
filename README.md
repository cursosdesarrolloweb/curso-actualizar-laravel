# Tickets

Aplicación de soporte de una empresa pequeña: los clientes abren tickets con adjuntos, los agentes responden y clasifican, y un administrador gestiona los roles. Tiene también una API con tokens para una app móvil y recibe tickets por correo con un webhook.

Es el proyecto de los cursos de actualización de Laravel de [CursosDesarrolloWeb.es](https://www.cursosdesarrolloweb.es). El tag `laravel-10` es el punto de partida: Laravel 10 sobre PHP 8.1, tal como lo dejaría un equipo a principios de 2024.

## Qué lleva

- Laravel 10 con Breeze (Blade) para el login, el registro y el perfil.
- Spatie Permission 5 con los roles `admin`, `agent` y `customer`.
- Sanctum 3 para los tokens de la API (`/api/tokens` y `/api/tickets`).
- Livewire 3 en el listado de tickets, con búsqueda en vivo.
- El comando `tickets:close-stale`, programado cada hora en `app/Console/Kernel.php`.
- Tests con Pest 2, análisis estático con Larastan 2 en nivel 5 y estilo con Pint.

## Requisitos

- Docker, con Docker Desktop en macOS y Windows (WSL2) o Docker Engine en Linux.
- Git.

No hace falta tener PHP ni Composer instalados: todo se ejecuta con [Laravel Sail](https://laravel.com/docs/10.x/sail).

## Levantarlo con Sail

Con el repositorio clonado, instala las dependencias de Composer con un contenedor de PHP 8.1:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Copia el `.env`, levanta los contenedores y prepara la base de datos:

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

La aplicación queda en [http://localhost](http://localhost). Los correos (verificación de email, contraseña olvidada) se escriben en `storage/logs/laravel.log`.

## Usuarios de prueba

Todos con la contraseña `password`:

| Email | Rol |
| --- | --- |
| `admin@tickets.test` | admin |
| `agent@tickets.test` | agent |
| `customer@tickets.test` | customer |

## Tests y calidad

```bash
./vendor/bin/sail test
./vendor/bin/sail bin phpstan analyse
./vendor/bin/sail bin pint --test
```

Los tests usan la base de datos `testing` que crea Sail en MySQL.
