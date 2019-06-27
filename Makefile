dev:
	@docker-compose down && \
        	docker-compose build --pull --no-cache && \
        	docker-compose up -d --remove-orphans && \
			make composer-install

test:
	@docker-compose down && \
        docker-compose build --pull --no-cache && \
        docker-compose -f docker-compose.yml -f docker-compose.test.yml \
        up --build -d --remove-orphans

database-create: 
	docker-compose exec php bin/console doctrine:database:create && \
	docker-compose exec php bin/console doctrine:schema:create --quiet

database-update:
	docker-compose exec php bin/console doctrine:schema:update --force

database-migrate:
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

composer-install:
	docker-compose exec php composer install

composer-update:
	docker-compose exec php composer update

oauth2-key:
	docker-compose exec php ./oauth-key.sh

behat:
	make database-create && \
	docker-compose exec php vendor/bin/behat

reload-schema:
	docker-compose exec php bin/console doctrine:schema:drop --force && \
	docker-compose exec php bin/console doctrine:schema:update --force

fixtures:
	docker-compose exec php bin/console doctrine:fixtures:load --no-interaction

reload-fixtures:
	make reload-schema && \
	make fixtures
