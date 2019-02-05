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
	docker stop -t 0 $(NAME)
	docker rm -f $(NAME)

dev:
	docker stop -t 0 $(NAME)-dev
	docker rm -f $(NAME)-dev
	docker run -d -p 80:80 -v ${PWD}/public:/var/www/html --name $(NAME)-dev php:7.0-apache