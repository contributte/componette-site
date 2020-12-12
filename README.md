<h1 align=center>Componette</h1>

<p align=center>
   Addons, extensions & components <a href="https://componette.org">portal</a> for <a href="https://nette.org">Nette Framework</a> with smooth searching and categorization.
</p>

<p align=center>
	<a href="https://componette.org"><img src="https://rawcdn.githack.com/f3l1x/xsource/b2663bd230b4ca50521fe6c7c554e484dd91e24d/assets/componette.png" alt="Componette" title="Componette" width="400"></a>
</p>

<p align=center>
    <a href="http://bit.ly/ctteg" rel="nofollow"><img src="https://img.shields.io/gitter/room/contributte/contributte.svg"></a>
    <a href="https://travis-ci.org/planette/componette" rel="nofollow"><img src="https://img.shields.io/travis/planette/componette.svg"></a>
    <a href="http://isitmaintained.com/project/planette/componette" rel="nofollow"><img src="https://isitmaintained.com/badge/open/planette/componette.svg"></a>
    <a href="http://isitmaintained.com/project/planette/componette" rel="nofollow"><img src="https://isitmaintained.com/badge/resolution/planette/componette.svg"></a>
</p>

<p align=center>
🕹 <a href="https://f3l1x.io">f3l1x.io</a> | 💻 <a href="https://github.com/f3l1x">f3l1x</a> | 🐦 <a href="https://twitter.com/xf3l1x">@xf3l1x</a>
</p>

----

## Requirements

* PHP 7.4+
* NodeJS 12+
* Caddy 0.11+
* MariaDB 10.3+
* **Docker** [optionally]

## How to develop

### Backend

- Clone this repo (`git@github.com:planette/componette.git`).
- Rename `app/config/config.local.sample` to `config.local.neon` and fill parameters (database, github token, etc).
- Run `composer install`.
- Run migration via `bin/console migrations:continue` follow steps.
- Start webserver `NETTE_DEBUG=1 php -S 0.0.0.0:8000 -t www`

### Frontend

- Run `npm install`
- Run `gulp deploy` (it compiles once all JS/CSS files)

For developing you can use `gulp watch`, it's monitor every **CSS** and **JS** files in `<project>/www/assets`.

## How to contribute

I very appreciate you contributing work, these tools keep on eye and help me to keep a high code standard.

### Automated tasks

This project has a few tasks you should fired before you prepare PR.

- **Quality Assurance** - checks PHP syntax errors and codestyle

    ```
    make qa
    ```

- **Nette\Tester** - runs unit & integration tests

    ```
    make tests
    ```

- **PHPstan** - runs static analyse

    ```
    make phpstan
    ```
