HOST := hostname -I

run:
	@echo "------------------------> Running Server <------------------------"
	php artisan serve

migrate:
	php artisan migrate

migrate-status:
	php artisan migrate:status


reset:
	php artisan migrate:reset


migrate-refresh:
	php artisan migrate:refresh

clear-laravel:
	php artisan cache:clear

bot-clear:
	php artisan config:cache
	php artisan config:clear

rd:
	npm run dev

install:
	@echo "Installing all system dependencies using apt-get"
	rm -rf .env
	sudo bash install.sh
	sudo chmod 777 .env
	php artisan config:clear
	sudo php artisan key:generate
	php artisan migrate

db:
	@echo "Options Database"
	sudo scripts/database.sh

mailserver:
	./tools/bin/mailhog &
	@echo "MailHog opened ..."

options:
	@echo
	@echo ----------------------------------------------------------------------
	@echo "   >>>>>                 TramiteSip               <<<<<   "
	@echo ----------------------------------------------------------------------
	@echo
	@echo "   - install     SETTINGS=[settings]    Install App and their dependencies"
	@echo "   - superuser   SETTINGS=[settings]    Create a super user in production"
	@echo "   - serve       SETTINGS=[settings]    Serve project for development"
	@echo "   - mail_server SETTINGS=[settings]    Open the Development Mail Server"
	@echo "   - test        SETTINGS=[settings]    Run laravel test cases"
	@echo "   - constance   SETTINGS=[settings]    settings laravel contance"
	@echo "   - sudo service apache2 restart    settings Laravel contance"
	@echo "   - sudo /etc/init.d/mysql restart    settings Laravel contance"
	@echo
	@echo ----------------------------------------------------------------------

clean_mode:
	rm -rf node_modules
	rm -rf static/dist

lint:
	@npm run lint --silent

dump:
	php artisan dump-server

clear:
	php artisan cache:clear
	php artisan config:cache
	php artisan route:clear
	php artisan view:clear
	php artisan optimize

tunnel:
	./ngrok http 8000

img:
	php artisan storage:link

version:
	php artisan --version

production:
	cp -r ./deploy/.env.production .env

dev:
	cp -p ./deploy/.env.dev .env

share:
	@echo ----------------------------------------------------------------------
	@echo "   >>>>>  Listo para compartir Proyecto        <<<<<   "
	@echo ----------------------------------------------------------------------
	php artisan serve --host=0.0.0.0 --port=8000


seed:
	php artisan db:seed --class=SocialSeeder

refresh:
	php artisan migrate:refresh

key-generate:
	php artisan key:generate

install-sip:
	@if exist composer.lock del /f composer.lock
	@if exist package-lock.json del /f package-lock.json
	npm install
	composer install

devs:
	php artisan variables