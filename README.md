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
