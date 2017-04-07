# CHANGELOG

## 1.3.0-dev

- Fix: after deploy errors
- Fix: invalid links on homepage [#88]

## 1.2.0 [06.04.2017]

- Feature: PHP 7.1
- Feature: layout 2.0
- Feature: strict coding standard
- Feature: PSR4
- Drop: support bower
- Fix: order addons releases
- Fix: addons tabs URL 
- Feature: improve search (thanks @artemevsin)[#81][#67]
- Composer: update dependencies
- Fix: Bower/Composer URL in http client
- Feature: added smartlook
- Improvement: load github images via GithubService
- Improvement: synchronize Github commands
- Fix: fix status page

## 1.1.0 [18.10.2016]

- Improvement: download github releases in html mode
- Improvement: better readme [#78]
- Improvement: addon sidebar [#77]
- Feature: added addon releases
- Improvement: common curl client
- Improvement: finalize symfony commands
- Fix: do not disable curl ssl verify [#60]
- Feature: added report page/link [#29]
- Fix: image resolving also over src [#64]
- Improvement: sync homepage headline links 
- Improvement: convert EOF to unix style [#57]
- Improvement: update composer.json (autoloading, classmap, etc) 
- Feature: added editorconfig [#57]
- Feature: track composer.json [#59]
- Feature: update to Nette 2.4, Nette\Tester 2.0

## 1.0.7 [04.06.2016]

- Improvement: added waffle badge to Readme
- Improvement: added github-like buttons
- Improvement: addon detail - added link to github [#55]
- Feature: added bower install (same as composer require)
- Improvement: github-like copy & paste title
- Fix: autofocus search form on Homepage
- Feature: change opensearch suggestion
- Fix: default ordering by owner / tag [#52]
- Monitoring: monitor composer clipboard && opening embedded images

## 1.0.6 [11.03.2016]

- Cron: weekly tasks
- Improvement: added simple lightbox [#49]
- Improvement: cache page all [#51]
- Improvement: fixed too long package name overflows [#47]
- Improvement: search autofocus only on homepage [#48]
- Feature: cleaning avatars over CLI

## 1.0.5 [26.02.2016]

- Feature: opensearch suggestion
- Feature: display all addons byt categories
- Feature: split front modules to Api & Portal
- Improvement: added some addon tags
- Feature: added simple admin module
- Fix: replace & with &amp; at opensearch
- Fix: RSS feed 
- Fix: gitter link

## 1.0.4 [12.02.2016]

- Fix: change gitter link
- Improvement: update links at github readmes
- Improvement: don't link php-ext dependencies in addon detail to packagist [#42]
- Feature: change menu structure (show connected addons at badge, added highlighted items) [#43]
- Feature: download readme in HTML format from Github, use github design for addon detail, change syntax highlighter
- Feature: added theme for chrome on mobile
- Improvement: revert addon fullname to `owner/name` [#41]
- Improvement: layout
- Improvement: added final to classes
- Feature: added google analytics campaigns (rss + opensearch)
- Improvement: simplify RSS (move logic to RssFactory)

## 1.0.3 [29.01.2016]

- Feature: added new relic monitoring
- Improvement: added tooltip in addon requirements [#28]
- Improvement: use latests hightligh.js (yaml plugin)
- Improvement: use https at badges
- Feature: added rss [#30]
- Docker/Nginx: provide HSTS [#33]

## 1.0.2 [24.01.2016]

- Improvement: rename choosen -> chosen, fix chosen sprite
- Feature: added less task to gulp [#31]
- Feature: click & copy composer require in addon detail
- Feature: added gulp
- Improvement: templates optimization (seo, alt, title, microformat, twitter)
- Feature: added github ribbon
- Fixed: cli processing
- Feature: added links on categories at homepage
- Feature: added new sorting by last added
- Feature: design tables in addon detail [#20]
- Fixed: layout on ipad2 and all others [#24]
- Feature: count queued addons in footer statistics
- Improvement: added google validation meta tag
- Improvement: mobile layouts [#25] [#26]
- Feature: sitemap generator [#27]
- Improvement: markdown relative link overriding [#22]

## 1.0.1 [13.01.2016]

- Feature: Nextras\ORM 2.0@dev
- Feature: addon charts (stats)
- Feature: footer stats
- Improved: split entity to addon => github, composer & bower
- Typo: package -> addon
- Other: new domain
- Other: docker configuration

### alpha2 [7.10.2015]
- Feature: bower packages
- Fixed: routing with dot(.)
- Improved: CLI tasks
- Improved: search degisn
- Improved: meta info design (thanks to looky)
- Improved: few tiny things

### alpha1 [5.10.2015]
- Feature: new landing page
- Feature: new searching / sorting / ordering
- Feature: modal for new packages
- Fixed: bold font in `<pre><code>`
- Improved: responsibility

## 1.0.0 [1.10.2015]
- First version
