# Chat API

## Installation Steps
We are using laravel sail read more about it from [Sail docs](https://laravel.com/docs/11.x/sail)

To install project on your local machine, run the following commands:-

1. Build sail services
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

2. Run docker-compose services: `sail up -d`
3. Migrate the database tables with seeders: `sail artisan migrate --seed`
4. Generate a new passport client: `sail php artisan passport:client --password` 
5. Save passport generated client-id and secret to your postman environment variables
6. Go to [Localhost:8082](http://localhost:8082/)
7. In order to stop services: `sail stop`

## Running services
1. Application Server:  `Exposed Port: 8082`
2. Mysql Server: `Exposed Port: 3309`
3. PhpMyAdmin: `Exposed Port: 3310`
