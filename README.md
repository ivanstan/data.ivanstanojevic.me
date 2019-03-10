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
* User avatars (Gravatar and ui-avatar.com as fallback)
* Bootstrap 4 frontend

## Installation

1. Install php dependencies `composer install`
2. Install javascript dependencies `npm install`
3. Setup database connection in `.env`<br/>
   More info: https://symfony.com/doc/current/configuration/external_parameters.html
4. Create database `bin/console doctrine:database:create`
5. Execute database migrations `bin/console doctrine:migrations:migrate`
6. Create user `bin/console user:create`

## Developer features
* Adding URL parameters `info`, `warning`, `danger`, `success`
  anywhere in application will render corresponding flash message.
* Endpoints `/_error/{http_code}` (e.g. /_error/404) will give preview of http error page.
* Endpoints `/_email/recovery`, `/_email/verify`, `/_email/invite` will show email templates
  for corresponding email actions.
