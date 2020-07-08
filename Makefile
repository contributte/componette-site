app=app
bin=vendor/bin
node=node_modules/.bin
temp=temp
tests=tests
ts-webpack=$(node)/cross-env TS_NODE_PROJECT='webpack/tsconfig.json' TS_NODE_TRANSPILE_ONLY=true
webpack=webpack
dirs:=bin $(app) $(tests)

# Setup

autoload:
	composer dump-autoload

build:
	$(ts-webpack) NODE_ENV=production $(node)/webpack --config $(webpack)/webpack.prod.ts --progress

dev:
	$(ts-webpack) $(node)/webpack-dev-server --config $(webpack)/webpack.dev.ts

rm-cache:
	rm -rf $(temp)/cache

reset: rm-cache autoload

serve:
	NETTE_DEBUG=1 php -S 0.0.0.0:8000 -t www

# Tests

test:
	$(bin)/tester -s -p phpdbg --colors 1 -C $(tests)/cases

test-coverage:
	$(bin)/tester -s -p phpdbg --colors 1 -C -d extension=xdebug.so --coverage $(temp)/coverage.xml --coverage-src $(dirs)

# QA

codefixer:
	$(bin)/codefixer $(dirs)

codesniffer:
	$(bin)/codesniffer $(dirs)

phpstan:
	$(bin)/phpstan analyse

prettier:
	$(node)/prettier --check "**/*.js"

prettier-fix:
	$(node)/prettier --write "**/*.js"

ts:
	$(node)/tsc --noEmit --project tsconfig.json

fix-php: reset codefixer qa-php

fix-ts: prettier-fix qa-ts

fix: fix-php fix-ts

qa-php: codesniffer phpstan

qa-ts: ts prettier

qa: codesniffer phpstan ts prettier
