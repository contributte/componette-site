<h1 align=center>Componette</h1>

<p align=center>
   Addons, extensions & components <a href="https://componette.org">portal</a> for <a href="https://nette.org">Nette Framework</a> with smooth searching and categorization.
</p>

<p align=center>
	<a href="https://componette.org"><img src="https://api.microlink.io?url=https://componette.org&screenshot=true&meta=false&embed=screenshot.url"></a>
</p>

<p align=center>
  <a href="https://github.com/contributte/componette-site/actions"><img src="https://badgen.net/github/checks/contributte/componette-site/master?tracy=300"></a>
  <a href="https://github.com/contributte/componette-site"><img src="https://badgen.net/github/license/contributte/componette-site"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://componette.org">componette.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/xf3l1x">@xf3l1x</a>
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

- Clone this repo (`git@github.com:contributte/componette-site.git`).
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

```bash
make qa
```

- **Nette\Tester** - runs unit & integration tests

```bash
make tests
```

- **PHPstan** - runs static analyse

```bash
make phpstan
```
