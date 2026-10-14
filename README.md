# notes-app

Jednoduchá aplikácia na správu poznámok – Symfony 7.4 LTS (PHP 8.4), MariaDB 12.3 LTS, Vue 3.

Plán vývoja: [docs/plan.md](docs/plan.md)

## Požiadavky
- Docker s Docker Compose v2
- voľné porty `8080` (aplikácia) a `3306` (databáza) – dajú sa zmeniť v `.env`

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
   Kým inštalácia nedobehne, Nginx vracia `502 Bad Gateway` – priebeh uvidíš cez `docker compose logs -f app`.

   > **Poznámka:** `composer install` sa spúšťa automaticky len vtedy, keď chýba priečinok `backend/vendor/`.
   > Ak sa po `git pull` zmenil `composer.lock`, spusti ho ručne:
   > ```bash
   > docker compose exec app composer install
   > ```

3. Aplikácia beží na [http://localhost:8080](http://localhost:8080).

## Služby

| Služba | Popis                         | Port               |
|--------|-------------------------------|--------------------|
| `web`  | Nginx                         | `8080` (`APP_PORT`) |
| `app`  | PHP 8.4 FPM + Symfony         | –                  |
| `db`   | MariaDB 12.3                  | `3306` (`DB_PORT`)  |

## Užitočné príkazy

```bash
# stav kontajnerov
docker compose ps

# logy (napr. pri prvom štarte, kým prebieha composer install)
docker compose logs -f app

# Symfony konzola
docker compose exec app bin/console

# Composer
docker compose exec app composer require <balíček>

# pripojenie k databáze
docker compose exec db sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'

# zastavenie
docker compose down

# zastavenie a zmazanie dát databázy
docker compose down -v
```
