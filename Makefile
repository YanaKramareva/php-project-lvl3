install:
	composer install

validate:
	composer validate

lint:
	composer exec phpcs -- --standard=PSR12 src bin tests

coverage:
	composer run-script test -- --coverage-clover build/logs/clover.xml

test:
	composer run-script test

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests

phpstan:
	composer exec phpstan -- analyse -l 5 src tests



