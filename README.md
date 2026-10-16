# notes-app

A simple notes management app – Symfony 7.4 LTS (PHP 8.4), MariaDB 12.3 LTS, Vue 3.

## Features
- List of notes with a priority filter (all / high / medium / low)
- Create, edit and delete notes
- Master–detail layout (list + detail), responsive for mobile
- REST API documented with Swagger UI

## Tech stack

| Part     | Technologies |
|----------|--------------|
| Backend  | PHP 8.4, Symfony 7.4 LTS, Doctrine ORM, Symfony Validator, NelmioApiDocBundle |
| Database | MariaDB 12.3 LTS |
| Frontend | Vue 3 (Composition API, TypeScript), Vite, Vue Router, axios, vue-sonner |
| Tooling  | Docker Compose, PHPUnit, ESLint + Oxlint, Prettier |

## Requirements
- Docker with Docker Compose v2
- Free ports `5173` (frontend), `8080` (API) and `3306` (database) – they can be changed in `.env`

## Getting started

1. Create the configuration file and set the database passwords in it:
   ```bash
   cp .env.example .env
   ```

2. Build and start the containers:
   ```bash
   docker compose up -d --build
   ```
   On the first start the PHP dependencies are installed automatically (`composer install`), which may take a while.
   The frontend JS dependencies (`npm install`) are checked and installed on every start of the `frontend` container.
   Until the installation finishes, Nginx returns `502 Bad Gateway` – follow the progress with `docker compose logs -f app`.

   > **Note:** `composer install` runs automatically only when the `backend/vendor/` directory is missing.
   > If `composer.lock` changed after `git pull`, run it manually:
   > ```bash
   > docker compose exec app composer install
   > ```

   Database migrations run automatically on every start of the `app` container.

3. (Optional) Load sample data (5 notes):
   ```bash
   docker compose exec app bin/console doctrine:fixtures:load
   ```
   **Warning:** the command first deletes all existing notes.

4. The frontend runs at [http://localhost:5173](http://localhost:5173), the API at [http://localhost:8080/api](http://localhost:8080/api).
   The Vite dev server proxies `/api/*` requests to Nginx, so the frontend and the API share the same origin (no CORS needed).

## API documentation

Swagger UI (the API can be tried out directly): [http://localhost:8080/api/doc](http://localhost:8080/api/doc)
OpenAPI specification (JSON): [http://localhost:8080/api/doc.json](http://localhost:8080/api/doc.json)

| Method   | Endpoint          | Description                                   |
|----------|-------------------|-----------------------------------------------|
| `GET`    | `/api/notes`      | List of notes, optional `?priority=low\|medium\|high` |
| `POST`   | `/api/notes`      | Create a note                                 |
| `GET`    | `/api/notes/{id}` | Note detail                                   |
| `PATCH`  | `/api/notes/{id}` | Update a note (only the sent fields change)   |
| `DELETE` | `/api/notes/{id}` | Delete a note                                 |

Errors are returned as JSON in the RFC 9457 (problem details) structure; validation errors (422) contain a `violations` list.

## Services

| Service    | Description                | Port                     |
|------------|----------------------------|--------------------------|
| `frontend` | Node 24 + Vite (Vue 3)     | `5173` (`FRONTEND_PORT`) |
| `web`      | Nginx                      | `8080` (`APP_PORT`)      |
| `app`      | PHP 8.4 FPM + Symfony      | –                        |
| `db`       | MariaDB 12.3               | `3306` (`DB_PORT`)       |

## Project structure

```
backend/src/
├── Controller/Api/Note/   single-action controllers, one per endpoint
├── Dto/Request|Response/  API input and output
├── Entity/  Enum/         Note entity, Priority enum
├── Repository/            database queries
├── Exception/             domain exceptions (e.g. note not found → 404)
├── Service/NoteService    business logic
└── DataFixtures/          sample data

frontend/src/
├── api/                   HTTP calls (axios) and error helpers
├── composables/           logic with state – useNotes(), useNoteSubmit()
├── components/base/       generic components (button, confirm dialog)
├── components/notes/      note components (list, form, filter, …)
├── views/                 screens rendered by the router (create, edit)
├── router/                routes – the open note lives in the URL
└── assets/styles/         design tokens and base styles
```

## Key design decisions

### Environment
- **Docker setup close to production** – Nginx + PHP-FPM + MariaDB, the same combination a Symfony app typically runs on a server. Migrations run automatically on start, so the app works after a single `docker compose up`.
- **LTS versions** (Symfony 7.4, MariaDB 12.3, Node 24) – long-term support and stability over the newest features.

### Backend
- **Thin controllers, logic in a service** – one single-action controller per endpoint only translates HTTP to a `NoteService` call and back. The business logic is in one place and does not depend on HTTP.
- **Request DTOs + Symfony Validator** – input is mapped to typed objects (`#[MapRequestPayload]`, `#[MapQueryString]`) and validated declaratively with attributes, so controllers contain no manual parsing or validation.
- **Always-valid entity** – a `Note` can only be created through its constructor with all required values; setters exist only for updating.
- **Priority as an int-backed enum** – stored as `SMALLINT` (easy to sort and index, no database `ENUM` to migrate), exposed in the API as readable labels `low` / `medium` / `high`.
- **List wrapped in `{ "data": [...] }`** – leaves room for pagination or metadata later without breaking the API.

### Frontend
- **Master–detail layout** – the list stays visible while a note is open, so the user does not lose context; on mobile only one panel is shown at a time. Priority is shown as a colored stripe and badge, so it can be scanned at a glance.
- **Presentational components + composables** – components only display data and emit events; logic with state lives in composables (`useNotes()` for the shared list and filter, `useNoteSubmit()` for create/edit submit handling).
- **Validation on two levels** – the form checks required fields for instant feedback; the backend stays the source of truth and its 422 errors are shown in the same place and style.

### Possible improvements
- Warning about unsaved changes when leaving the form
- Pagination or infinite scroll for long lists
- Frontend unit tests (composables) and static analysis on the backend (PHPStan, PHP-CS-Fixer)

## Tests

```bash
docker compose exec app composer test
```
The command migrates the test database `notes_app_test` and runs PHPUnit. Every test runs in a transaction that is rolled back afterwards, so no data stays in the database.

> **Note:** the test database is created by a MariaDB init script only on the first start with an empty volume.
> If your volume is older, recreate it (deletes data): `docker compose down -v && docker compose up -d`

## Editor support

The frontend `node_modules` live only inside the `frontend` container, so an editor on the host may report errors like *Cannot find module 'vue'*. To get type checking in the editor, install the dependencies on the host as well (requires Node 24):
```bash
cd frontend && npm install
```
The container keeps using its own `node_modules`.

## Useful commands

```bash
# container status
docker compose ps

# logs (e.g. on the first start while composer install runs)
docker compose logs -f app

# Symfony console
docker compose exec app bin/console

# migration status / new migration after an entity change
docker compose exec app bin/console doctrine:migrations:status
docker compose exec app bin/console doctrine:migrations:diff

# Composer
docker compose exec app composer require <package>

# frontend – lint, type check, formatting, new package
docker compose exec frontend npm run lint
docker compose exec frontend npm run type-check
docker compose exec frontend npm run format
docker compose exec frontend npm install <package>

# connect to the database
docker compose exec db sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'

# stop
docker compose down

# stop and delete the database data
docker compose down -v
```
