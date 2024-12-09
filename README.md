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

### Facebook

Follow this document for a detailed instructions
https://developers.facebook.com/docs/development/register/
https://developers.facebook.com/docs/development/create-an-app/server-to-server-apps
https://www.ayrshare.com/facebook-api-how-to-post-and-get-analytics-using-the-facebook-api/

### LinkedIn
https://www.getphyllo.com/post/linkedin-api-ultimate-guide-on-linkedin-api-integration

### Twitter
https://developer.x.com/en/docs/x-api/getting-started/getting-access-to-the-x-api
