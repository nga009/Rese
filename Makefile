init:
	docker-compose up -d --build
	docker-compose exec php composer install
	docker-compose exec php composer require stripe/stripe-php
	docker-compose exec php cp .env.local .env
	mkdir ./src/storage/app/public/shop_images
	docker-compose exec php php artisan key:generate
	docker-compose exec php php artisan storage:link
	docker-compose exec php chmod -R 777 storage bootstrap/cache
	@make fresh

init-production:
	docker-compose up -d --build
	docker-compose exec php composer install
	docker-compose exec php composer require stripe/stripe-php
	docker-compose exec php cp .env.production .env
	mkdir ./src/storage/app/public/shop_images
	docker-compose exec php php artisan key:generate
	docker-compose exec php php artisan storage:link
	docker-compose exec php chmod -R 777 storage bootstrap/cache
	docker compose exec php php artisan migrate:fresh

fresh:
	docker compose exec php php artisan migrate:fresh --seed

restart:
	@make down
	@make up

up:
	docker-compose up -d

down:
	docker compose down --remove-orphans

cache:
	docker-compose exec php php artisan cache:clear 
	docker-compose exec php php artisan config:cache 
stop:
	docker-compose stop