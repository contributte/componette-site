app=app
bin=vendor/bin
temp=temp
tests=tests
dirs:=$(app) $(tests)

# Setup

autoload:
	composer dump-autoload

rm-cache:
	rm -rf $(temp)/cache

reset: rm-cache autoload

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

qa: reset codefixer codesniffer phpstan
