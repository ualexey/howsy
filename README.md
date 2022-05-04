* Clone this progect

* Run docker:
[^1]:
**docker-compose up**

* Install dependencies:
[^1]:
**composer install**

* Migrate DB structure:
[^1]:
**php bin/console doctrine:migrations:migrate**

* Load DB data:
[^1]:
**php bin/console app:create-dbdata**

* Create PHPUnit test DB:
[^1]:
**php bin/console --env=test doctrine:database:create**
[^1]:
**php bin/console --env=test doctrine:schema:create**

* Load test DB data:
[^1]:
**php bin/console --env=test app:create-dbdata**
[^1]:
* Run tests :+1:
[^1]:
**php bin/phpunit**
