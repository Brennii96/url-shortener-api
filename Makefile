start:
	docker compose up -d

stop:
	docker compose down

cc:
	docker compose run --rm app php artisan optimize:clear

run-tests:
	docker compose run --rm app php artisan test
