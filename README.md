# Stripe Local PHP
Redirect Stripe requests for local development in a PHP environment

### Run the following CLI command:
- Install dependencies:
```sh
composer install
```
- Login to Stripe:
```sh
stripe login
```
- Start listener:
```sh
stripe listen --forward-to stripe.local
```
Done, requests will already be redirected automatically. Have a fun development!
