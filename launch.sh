#!/bin/bash

#Check for docker installation
if [ -x "$(command -v docker)" ]; then
    echo "docker already installed, skipping"
else
    echo "Installing docker"

    apt-get update

    apt-get install docker-ce docker-ce-cli containerd.io -y

    echo "docker installed successfully"
fi

#Check for docker-compose installation
if [ -x "$(command -v docker-compose)" ]; then
    echo "docker-compose already installed, skipping"
else
    apt install docker-compose
    echo "docker-compose installed successfully"
fi

chmod -R 755 storage

touch database/database.sqlite

docker-compose up -d --build

docker-compose exec php-service php /var/www/html/artisan key:generate --force;
docker-compose exec php-service php /var/www/html/artisan cache:clear;
docker-compose exec php-service php /var/www/html/artisan config:clear;
docker-compose exec php-service php /var/www/html/artisan route:clear;
