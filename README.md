# Budget Tracker by hvck

This is a simple API to track your purchases.
It is designed for scalability and can be easily
used in various platforms such as Web and Mobile.

For example, you can use my [Budget Tracker Frontend App](https://github.com/hvckdev/budget-tracker-frontend).

There aren't a many features, but u can add it by yourself.

Probably, someday, I will add there some analytic features.

## Requirements

- PHP >= 8.1
- Composer
- MySQL
- ext_bcmath enabled

or

- Docker

## Deploy

```bash
git clone https://github.com/hvckdev/budget-tracker-backend.git && \
cd budget-tracker-backend && \
composer install && \
php bin/console lexik:jwt:generate-keypair
```

Then configure your MySQL connection, then enjoy.

Have a nice day 💖