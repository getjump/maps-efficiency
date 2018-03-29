Installation:

docker-compose up -d

docker exec mapsefficiency_app php artisan migrate
docker exec mapsefficiency_app php artisan db:seed