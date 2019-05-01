<div align="center">
    <br/>
    <img src="https://cdn.jsdelivr.net/gh/f3l1x/xsource/assets/componette.png" alt="Componette" title="Componette" width="400">
    <br/>
    <br/>
    <p>
       Addons, extensions & components portal for <a href="https://nette.org">Nette Framework</a> with smooth searching and categorization.
    </p>
    <p>Check the <a href="https://componette.com"><strong>real demo</strong></a>.</p>
    <br/>
    <p>
        <a href="https://gitter.im/componette/componette" rel="nofollow"><img src="https://img.shields.io/gitter/room/componette/componette.svg"></a>
        <a href="https://travis-ci.org/planette/componette.com" rel="nofollow"><img src="https://img.shields.io/travis/planette/componette.com.svg?style=flat-square"></a>
        <a href="http://isitmaintained.com/project/componette/componette.com" rel="nofollow"><img src="https://isitmaintained.com/badge/open/componette/componette.com.svg"></a>
        <a href="http://isitmaintained.com/project/componette/componette.com" rel="nofollow"><img src="https://isitmaintained.com/badge/resolution/componette/componette.com.svg"></a>
    </p>
    <p>
        <em>Follow <a href="http://twitter.com/xf3l1x">@xf3l1x</a> for more updates!</em>
    </p>
</div>

----

## Requirements

* PHP 7.3+
* NodeJS 10+
* Caddy 0.11+
* MariaDB 10.3+
* **Docker** [optionally]

## How to develop

### Backend

- Clone this repo (`git@github.com:planette/componette.com.git`).
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

- **Quality Assurance** - checks PHP syntax errors and codestyle

    ```
    composer qa
    ```

- **Nette\Tester** - runs unit & integration tests

    ```
    composer tester
    ```
