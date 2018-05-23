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

