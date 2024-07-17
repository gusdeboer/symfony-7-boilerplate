install:
	symfony composer install
	symfony console doctrine:migrations:migrate --no-interaction

start:
	docker compose up -d
	symfony server:start
	symfony console messenger:consume async --limit=20 --time-limit=600 --memory-limit=128M

stop:
	docker compose down
	symfony server:stop

phpstan:
	symfony composer update phpstan/phpstan
	vendor/bin/phpstan analyse src tests

php-cs-fixer:
	symfony composer update friendsofphp/php-cs-fixer
	vendor/bin/php-cs-fixer fix --ansi --verbose --diff

php-cs-fixer-dry-run:
	symfony composer update friendsofphp/php-cs-fixer
	vendor/bin/php-cs-fixer fix --ansi --verbose --diff --dry-run

phpunit:
	symfony php bin/phpunit

rector:
	symfony composer update rector/rector
	vendor/bin/rector process src --ansi --verbose

rector-dry-run:
	symfony composer update rector/rector
	vendor/bin/rector process src --ansi --verbose --dry-run
