#!/bin/bash
set -e

echo "==> Génération APP_KEY..."
php artisan key:generate --force --no-interaction

echo "==> Cache config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Migrations..."
php artisan migrate --force --no-interaction

echo "==> Seeders..."
php artisan db:seed --force --no-interaction || echo "Seeders déjà appliqués, on continue."

echo "==> Lien storage..."
php artisan storage:link --force || true

echo "==> Démarrage serveur sur port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
