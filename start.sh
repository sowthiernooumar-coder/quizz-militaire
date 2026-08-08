#!/bin/bash
set -e

echo "==> Génération de la clé si absente..."
php artisan key:generate --no-interaction --force 2>/dev/null || true

echo "==> Migrations..."
php artisan migrate --force --no-interaction

echo "==> Seeders..."
php artisan db:seed --force --no-interaction 2>/dev/null || true

echo "==> Lien storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Démarrage du serveur..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
