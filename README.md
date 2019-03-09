# Symfony Login

## Features
* Login authentication via email and password
* Role system
* User management system
* Ability to create users from command line interface
* Password recovery via mail
* Invitations via one-time-login links
* Account email verification
* IP ban for suspicious user behavior
* User preferences (Timezone)
* Password change form
* Bootstrap 4 frontend

## Installation

1. Install php dependencies `composer install`
2. Install javascript dependencies `npm install`
3. Setup database connection in `.env`<br/>
   More info: https://symfony.com/doc/current/configuration/external_parameters.html
4. Create database `bin/console doctrine:database:create`
5. Execute database migrations `bin/console doctrine:migrations:migrate`
6. Create user `bin/console user:create`
