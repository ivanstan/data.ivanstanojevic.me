# Symfony Login

## Installation

1. Install php dependencies `composer install`
2. Install javascript dependencies `npm install`
3. Setup database connection in `.env`<br/>
   More info: https://symfony.com/doc/current/configuration/external_parameters.html
4. Create database `bin/console doctrine:database:create`
5. Execute database migrations `bin/console doctrine:migrations:migrate`
6. Create user `bin/console user:create`;
