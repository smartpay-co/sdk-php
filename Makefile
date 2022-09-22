.PHONY: integration-test GUARD

integration-test: guard-SMARTPAY_SECRET_KEY guard-SMARTPAY_PUBLIC_KEY guard-API_BASE
	vendor/bin/phpunit tests/Integrations --testdox

test: guard-SMARTPAY_SECRET_KEY guard-SMARTPAY_PUBLIC_KEY guard-API_BASE
	XDEBUG_MODE=coverage vendor/bin/phpunit tests --testdox --coverage-text --whitelist src/


guard-%: GUARD
	@ if [ -z '${${*}}' ]; then echo 'Environment variable $* not set.' && exit 1; fi

GUARD:
