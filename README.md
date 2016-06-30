# Componette

Modern useful #nette addons portal.

## Builds
[![Build Status](https://img.shields.io/travis/componette/componette.com.svg?style=flat-square)](https://travis-ci.org/componette/componette.com)

## Discussion
[![Gitter](https://img.shields.io/gitter/room/componette/componette.svg)](https://gitter.im/componette/componette)

## Activity

[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/componette/componette.com.svg)](http://isitmaintained.com/project/componette/componette.com)
[![Percentage of issues still open](http://isitmaintained.com/badge/open/componette/componette.com.svg)](http://isitmaintained.com/project/componette/componette.com)

[![Throughput Graph](https://graphs.waffle.io/componette/componette.com/throughput.svg)](https://waffle.io/componette/componette.com/metrics)


## Requirements

### Application

* PHP >= 5.6.0
* Nette packages

### Server

* PHP + Composer
* Nginx
* MariaDB/MySQL
* [**Docker**](https://github.com/componette/dockerfiles)

## Docker

This portal run in Docker container(s). You can see configurations in [**dockerfiles**](https://github.com/componette/dockerfiles) repository.

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

## How to contribute

I very appreciate you contributing work, these tools keep on eye and help me to keep a high code standard.

### Linter

```sh
$ sh bin/linter

PHP 7.0.7 | 10 parallel jobs
............................................................  60/123 (48 %)
............................................................ 120/123 (97 %)
...                                                          123/123 (100 %)
```

### CodeSniffer


```sh
$ sh bin/linter

............................................................  60 / 123 (49%)
............................................................ 120 / 123 (98%)
...

Time: 1.64 secs; Memory: 12Mb
```

### Tests

```sh
$ sh bin/tester

|_   _/ __)( __/_   _/ __)| _ )
  |_| \___ /___) |_| \___ |_|_\  v2.0.x

Note: No php.ini is used.
PHP 7.0.7-1~dotdeb+8.1 (cli) | php -n  | 8 threads

...


OK (x tests, 0.0 seconds)
```
