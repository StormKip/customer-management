## Installation
Please check the official Laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

[Docker Desktop](https://www.docker.com/products/docker-desktop) is recommended to run this project locally

Alternatively the [Laravel Homestead](https://laravel.com/docs/5.4/homestead) virtual machine is can be used to run this project locally.

`composer` and `NPM` are required in order to run this project locally.

**TL;DR command list after cloning this repo**

    cd customer-management
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate

**Build Docker Container**

    docker-compose up -d --build
