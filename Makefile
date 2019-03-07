COMPANY=smilo
AUTHOR=Elkan Roelen
NAME=smilopay
VERSION=latest
DIR = $(shell pwd)

build:
	docker build --no-cache -t $(NAME) .

start:
	docker run -d -p 80:80 --name $(NAME) $(NAME)

stop:
	docker stop -t 0 $(NAME) || true
	docker rm -f $(NAME) || true

dev:
	docker stop -t 0 $(NAME)-dev || true
	docker rm -f $(NAME)-dev || true
	docker run -d -p 80:80 -v ${PWD}/public:/var/www/html --name $(NAME)-dev php:7.0-apache