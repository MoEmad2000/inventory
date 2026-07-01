# Inventory App — Laravel + Docker Setup

This project runs on Docker with the following services:

- **app** — PHP 8.3-FPM (Laravel)
- **nginx** — Web server (port `8000`)
- **postgres** — PostgreSQL 16 database (port `5433`)
- **redis** — Redis 7 (port `6379`)

## Prerequisites

- Docker & Docker Compose installed
- Git

## 1. Clone the project

```bash
git clone https://github.com/MoEmad2000/inventory.git
cd inventory-app
```

If you're starting a fresh Laravel project instead of cloning one, make sure the Laravel source code exists in this directory before continuing (the Dockerfile only installs PHP/Composer, it doesn't create the app).


## 2. Create your `.env` file

Copy the example env file (or create one) and set the database/redis connection to match the Docker service names:

```bash
cp .env.example .env
```

Update these values in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=inventory
DB_USERNAME=postgres
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379
```

> Note: Inside Docker's internal network, containers talk to each other using the **service name** (`postgres`, `redis`) and the **internal port** (`5432`, `6379`) — not the host-mapped ports (`5433`, `6379` from your machine).

## 3. Build and start the containers

```bash
docker compose build
docker compose up -d
```

Check that all containers are running:

```bash
docker compose ps
```

## 4. Install PHP dependencies

```bash
docker compose exec app composer install
```

## 5. Generate the application key

```bash
docker compose exec app php artisan key:generate
```

## 6. Run database migrations

```bash
docker compose exec app php artisan migrate
```

Optionally, seed the database:

```bash
docker compose exec app php artisan db:seed
```

## 7. Set folder permissions (if needed)

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

## 8. Access the application

Open your browser at:

```
http://localhost:8000
```

## Useful commands

| Action | Command |
|---|---|
| Stop containers | `docker compose down` |
| Stop and remove volumes (⚠ deletes DB data) | `docker compose down -v` |
| View logs | `docker compose logs -f` |
| Enter app container shell | `docker compose exec app bash` |
| Run artisan commands | `docker compose exec app php artisan <command>` |
| Run composer commands | `docker compose exec app composer <command>` |
| Access PostgreSQL from host | `psql -h localhost -p 5433 -U postgres -d inventory` |

## Notes

- The `app` container has no `command` set, so it uses the default `php-fpm` entrypoint from the base image — this is correct for use with nginx via FastCGI.
- Postgres data persists in the named volume `postgres_data`. Data survives `docker compose down` but is deleted with `docker compose down -v`.
- If you change PHP extensions or system dependencies in the `Dockerfile`, rebuild with `docker compose build --no-cache`.