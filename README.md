# Seolve

A simple social media management tool.

# Installation

Clone the repository
```sh
git clone https://github.com/praem90/seolve-app
cd seolve-app
```

Install composer dependencies
```sh
composer install
```

Copy `.env.example` to `.env`
```sh
cp .env.example .env
```

Update the database credentials in the `.env` file

Generate the app key
```
php artisan key:generate
```

Run the migration
```
php artisan migrate
```

Serve the application
```
php artisan serve
```

Visit http://localhost:8000/register to create an account

Login to the account created and add a company

## What Next?

Create develper account on Facebook, LinkedIn and Twitter/X

Get the application's clientId and secrets and update the respective
variables on the `.env` file
