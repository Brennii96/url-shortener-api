start:
	docker compose up -d

stop:
	docker compose down

composer-install:
	docker compose run --rm app composer install

generate-key:
	docker compose run --rm app php artisan key:generate

cc:
	docker compose run --rm app php artisan optimize:clear

run-tests:
	docker compose run --rm app php artisan test
