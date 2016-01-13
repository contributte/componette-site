# Componette

New #nette addons portal. 

## Requirements

### Application

* PHP >= 5.6.0
* Nette packages

### Server

* PHP + Composer
* Nginx
* MariaDB/MySQL
* **Docker**

## Docker

This portal run in Docker container(s). You can see configurations in **docker** branch.

## How to run

- Clone this repo (`git@github.com:componette/componette.com.git`)
- Rename `app/config/config.local.sample` to `config.local.neon` and fill parameters
- Create database and setup tables (and fixtures)
- Run `composer install`
