COMPANY=smilo
AUTHOR=Elkan Roelen
NAME=explorer
VERSION=latest
NPM_BIN=node_modules/.bin/
DIR = $(shell pwd)

# Some good `forever` options.
FOREVER_OPTS = -p ./logs  \
		-l server_out.log \
		-o ./logs/server_out.log \
		-e ./logs/server_err.log \
		--append \
		--plain \
		--minUptime 1000 \
		--spinSleepTime 1000

build:
	npm install
	docker build --no-cache -t $(NAME) .

start: setupdirs
	docker run -d -p 80:80 -v $(DIR)/public:/var/www/html --name $(NAME) $(NAME)
	$(NPM_BIN)forever $(FOREVER_OPTS) $@ src/block-server.js

stop:
	docker stop -t 0 $(NAME)
	docker rm -f $(NAME)
	$(NPM_BIN)forever $(FOREVER_OPTS) $@ src/block-server.js

setupdirs:
  # creating required directories for `forever`
	mkdir -p logs
