start:
	php artisan serve

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.pgsql
	php artisan migrate
	npm install

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test --filter 'Tests\\Feature'

test-coverage:
	php artisan test --coverage-clover build/logs/clover.xml

lint:
	composer exec phpcs  -- --standard=PSR12 app bootstrap config lang tests

analyse:
	composer exec phpstan analyse -v -- --memory-limit=-4

lint-fix:
	composer exec  phpcbf app bootstrap config lang tests

deploy:
	git push heroku main

