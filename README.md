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

* PHP >= 5.6.0 (prefer ^7.0)
* Nette packages (prefer ^2.4)

### Server

* PHP + Composer + NodeJS
* WebServer (Nginx / Apache)
* MariaDB / MySQL
* [**Docker**](https://github.com/componette/dockerfiles) [optionally]

## Docker

This portal is running in isolated Docker container(s). You can see configurations in [**dockerfiles**](https://github.com/componette/dockerfiles) repository.

## How to run

### Backend

- Clone this repo (`git@github.com:componette/componette.com.git`).
- Rename `app/config/config.local.sample` to `config.local.neon` and fill parameters (database, github token, etc).
- Run `composer install`.
- Run migration via `bin/console migrations:continue` or manually in browser `<project>/migrations/run.php` and follow steps.

### Frontend

- Run `npm install`
- Run `gulp deploy` (it compiles once all JS/CSS files)

For developing you can use `gulp watch`, it's monitor every **CSS** and **JS** files in `<project>/www/assets`.

## How to contribute

I very appreciate you contributing work, these tools keep on eye and help me to keep a high code standard.

### Automated tasks

This project has a few tasks you should fired before you prepare PR.

- Linter - checks PHP syntax errors
    ```bash
    bin/linter
    ```
- CodeSniffer - checks codestyle
    ```bash
    bin/codesniffer
    ```

- Tests - checks code integrity
    ```sh
    bin/tester
    ```
