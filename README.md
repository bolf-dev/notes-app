# notes-app

Jednoduchá aplikácia na správu poznámok – Symfony 7.4 LTS (PHP 8.4), MariaDB 12.3 LTS, Vue 3.

## Požiadavky
- Docker s Docker Compose v2
- voľné porty `5173` (frontend), `8080` (API) a `3306` (databáza) – dajú sa zmeniť v `.env`

## Spustenie

1. Vytvor konfiguračný súbor a nastav v ňom heslá k databáze:
   ```bash
   cp .env.example .env
   ```

2. Postav a spusti kontajnery:
   ```bash
   docker compose up -d --build
   ```
   Pri prvom štarte sa automaticky nainštalujú PHP závislosti (`composer install`), môže to chvíľu trvať.
   JS závislosti frontendu (`npm install`) sa kontrolujú a inštalujú pri každom štarte kontajnera `frontend`.
   Kým inštalácia nedobehne, Nginx vracia `502 Bad Gateway` – priebeh uvidíš cez `docker compose logs -f app`.

   > **Poznámka:** `composer install` sa spúšťa automaticky len vtedy, keď chýba priečinok `backend/vendor/`.
   > Ak sa po `git pull` zmenil `composer.lock`, spusti ho ručne:
   > ```bash
   > docker compose exec app composer install
   > ```

   Databázové migrácie sa spúšťajú automaticky pri každom štarte kontajnera `app`.

3. (Voliteľné) Naplň databázu ukážkovými dátami (5 poznámok):
   ```bash
   docker compose exec app bin/console doctrine:fixtures:load
   ```
   **Pozor:** príkaz najprv zmaže všetky existujúce poznámky.

4. Frontend beží na [http://localhost:5173](http://localhost:5173), API na [http://localhost:8080/api](http://localhost:8080/api).
   Vite dev server presmerúva volania `/api/*` na Nginx, takže frontend a API sú pre prehliadač na rovnakom origine.

## Dokumentácia API

Swagger UI (dá sa v ňom API aj priamo skúšať): [http://localhost:8080/api/doc](http://localhost:8080/api/doc)
OpenAPI špecifikácia (JSON): [http://localhost:8080/api/doc.json](http://localhost:8080/api/doc.json)

## Služby

| Služba     | Popis                          | Port                     |
|------------|--------------------------------|--------------------------|
| `frontend` | Node 24 + Vite (Vue 3)         | `5173` (`FRONTEND_PORT`) |
| `web`      | Nginx                          | `8080` (`APP_PORT`)      |
| `app`      | PHP 8.4 FPM + Symfony          | –                        |
| `db`       | MariaDB 12.3                   | `3306` (`DB_PORT`)       |

## Testy

```bash
docker compose exec app composer test
```
Príkaz zmigruje testovaciu databázu `notes_app_test` a spustí PHPUnit. Každý test beží v transakcii, ktorá sa po ňom vráti, takže dáta v databáze nezostávajú.

> **Poznámka:** testovaciu databázu vytvára init skript MariaDB len pri prvom štarte s prázdnym volume.
> Ak máš volume z doby pred jej pridaním, vytvor ho nanovo (zmaže dáta): `docker compose down -v && docker compose up -d`

## Užitočné príkazy

```bash
# stav kontajnerov
docker compose ps

# logy (napr. pri prvom štarte, kým prebieha composer install)
docker compose logs -f app

# Symfony konzola
docker compose exec app bin/console

# stav migrácií / nová migrácia po zmene entity
docker compose exec app bin/console doctrine:migrations:status
docker compose exec app bin/console doctrine:migrations:diff

# Composer
docker compose exec app composer require <balíček>

# frontend – lint, kontrola typov, formátovanie, nový balíček
docker compose exec frontend npm run lint
docker compose exec frontend npm run type-check
docker compose exec frontend npm run format
docker compose exec frontend npm install <balíček>

# pripojenie k databáze
docker compose exec db sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'

# zastavenie
docker compose down

# zastavenie a zmazanie dát databázy
docker compose down -v
```
