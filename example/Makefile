PHP_BIN := $(shell which php)

help:
	@echo usage:
	@echo "\tmake install"
	@echo "\tmake server"

install:
	$(PHP_BIN) ../composer.phar install

server:
	$(PHP_BIN) \-S localhost:8080 -t ./web

.PHONY: help install server
