Installation process: 
- docker compose down
- docker compose up -d
- docker compose exec app composer update
- docker compose exec app php artisan migrate
- docker compose exec app php artisan db:seed
- docker compose exec app php artisan config:clear
- docker compose exec app php artisan config:cache
- docker compose exec app php artisan view:clear
- docker compose exec app php artisan route:clear
- docker compose exec app php artisan route:cache

Connection to the app in the container
- docker exec -it api-courselandia-ru-app bash
- docker exec -u root -it api-courselandia-ru-app /bin/bash

Connection to the database in the container
- docker exec -it db bash
- mysql -u ikit -p

- docker compose run --rm npm run dev
- UPDATE courses
  SET status = 'active',
  updated_at = '2024-06-01 00:00:00'
  WHERE status = 'draft'
  and deleted_at IS NULL
  and profession_ids IS NOT NULL
  and JSON_LENGTH(JSON_EXTRACT(profession_ids, '$')) != 0;

cd /var/www/courselandia.ru/data/www/courselandia.ru
docker compose build
docker compose up -d

cd /var/www/courselandia.ru/data/www/api.courselandia.ru
docker compose build
docker compose up -d
docker compose exec app composer update
docker compose run --rm npm install
docker compose run --rm npm run prod
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan route:cache

cd /var/www/courselandia.ru/data/www/admin.courselandia.ru
docker compose build
docker compose up -d


* * * * * cd /var/www/courselandia.ru/data/www/api.courselandia.ru && docker compose exec app php artisan schedule:run >> /dev/null 2>&1

scp -r /var/www/courselandia.ru/data/www/admin.courselandia.ru courselandia@188.93.211.156:/var/www/courselandia.ru/data/www/admin.courselandia.ru

