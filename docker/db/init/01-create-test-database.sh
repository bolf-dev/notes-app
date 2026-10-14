#!/bin/bash
# Runs only on the first start with an empty data volume.
# Creates the database used by PHPUnit (Doctrine appends "_test" in the test environment).
set -e

mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" <<SQL
CREATE DATABASE IF NOT EXISTS \`${MARIADB_DATABASE}_test\`;
GRANT ALL PRIVILEGES ON \`${MARIADB_DATABASE}_test\`.* TO '${MARIADB_USER}'@'%';
SQL
