#!/bin/bash
exec 1> >(logger -s -t $(basename $0)) 2>&1
cd /var/www/html/live
composer install
php artisan storage:link
#php artisan migrate:fresh --seed
php artisan migrate
#php artisan db:seed --class=SettingsSeeder
npm install && npm run dev
chmod -R 755 app/Components/Szamlazz/xmls app/Components/Szamlazz/pdf
chown -R www-data:www-data app/Components/Szamlazz/xmls app/Components/Szamlazz/pdf
rm -f $AFTERSCRIPT
