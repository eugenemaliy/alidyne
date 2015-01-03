php app/console doctrine:schema:update --force
php app/console cache:clear -e prod
chmod -R 777 app/cache
chmod -R 777 web
chmod -R 777 app/logs