# Componette

Modern useful #nette addons portal.

[![Gitter](https://img.shields.io/gitter/room/componette/componette.svg)](https://gitter.im/componette/componetteA)
[![Build Status](https://img.shields.io/travis/componette/componette.com.svg?style=flat-square)](https://travis-ci.org/componette/componette.com)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/componette/componette.com.svg)](http://isitmaintained.com/project/componette/componette.com)
[![Percentage of issues still open](http://isitmaintained.com/badge/open/componette/componette.com.svg)](http://isitmaintained.com/project/componette/componette.com)

## Requirements

### Application

* PHP >= 5.6.0
* Nette packages

### Server

* PHP + Composer
* Nginx
* MariaDB/MySQL
* [**Docker**](https://github.com/componette/componette.com/tree/docker)

## Docker

This portal run in Docker container(s). You can see configurations in **docker** branch.

## How to run

### Backend

- Clone this repo (`git@github.com:componette/componette.com.git`)
- Rename `app/config/config.local.sample` to `config.local.neon` and fill parameters
- Create database and setup tables (and fixtures)
- Run `composer install`

### Frontend

- Run `npm install`
- Run `gulp deploy`

For developing you can use `gulp watch`, it's monitor every **CSS** and **JS** files in `www/assets`.
