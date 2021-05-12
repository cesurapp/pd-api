# pdApi Bundle
Symfony 5 Restful Api Bundle

[![Packagist](https://img.shields.io/packagist/dt/appaydin/pd-api.svg)](https://github.com/appaydin/pd-api)
[![Github Release](https://img.shields.io/github/release/appaydin/pd-api.svg)](https://github.com/appaydin/pd-api)
[![license](https://img.shields.io/github/license/appaydin/pd-api.svg)](https://github.com/appaydin/pd-api)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/appaydin/pd-api.svg)](https://github.com/appaydin/pd-api)

* Supports XML and JSON response
* Error messages are collected under a single format.
* Language translation is applied to all error messages.
* Request body Transformer (JSON-XML) has been added.
* Normalizer has been added for form errors.
* Normalizer has been added for KnpPaginator.

Installation
---

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require appaydin/pd-api
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

#### Step 2: Enable the Bundle

With Symfony 5, the package will be activated automatically. But if something goes wrong, you can install it manually.

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
<?php
// config/bundles.php

return [
    //...
    Pd\ApiBundle\PdApiBundle::class => ['all' => true]
];
```
#### Step 3: Settings Bundle
```yaml
# config/packages/pd_api.yaml

pd_api:
    zone: ^/api
    default_accept: App\Entity\User
    default_accept: json
    allow_accept: ['json', 'xml']
```
#### Step 4: Settings Security.yaml
```yaml
# config/packages/security.yaml

security:
  providers:
    ...
    pdadmin_api:
      entity:
        class: App\Entity\User
        property: phone
  firewalls:
    ...
    api:
      pattern: ^/api
      stateless: true
      anonymous: true
      provider: pdadmin_api
      json_login:
        check_path: /api/auth/login
        failure_handler: lexik_jwt_authentication.handler.authentication_failure
      guard:
        authenticators:
          - lexik_jwt_authentication.jwt_token_authenticator
  access_control:
    - { path: ^/api/auth, roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/api, roles: IS_AUTHENTICATED_FULLY }
```
#### Step 5 (Optional): Create Login Endpoint

```php
# src/Controller/AuthorizationController.php

namespace App\Controller;

use Pd\ApiBundle\Controller\AbstractApiController;
use Pd\ApiBundle\Controller\LoginTrait;

class AuthorizationController extends AbstractApiController
{
    use LoginTrait;
}
```

```yaml
# config/routes.yaml
api:
  resource: ../src/Controller
  type: annotation
  prefix: api
```

Create API
---
```php
# src/Controller/ExampleApiController.php

use Pd\ApiBundle\Controller\AbstractApiController;
use Symfony\Component\Routing\Annotation\Route;

class ExampleApiController extends AbstractApiController
{
    #[Route("/home", name:"api.home", methods: ["GET"])]
    public function home() {
        return ['test'];
    }
}
```
