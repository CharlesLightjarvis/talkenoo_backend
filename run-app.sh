#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-app.sh`
# Run migrations, process the Nginx configuration template and start Nginx

# Start Reverb in the background
php artisan reverb:start &

# Run migrations, seed, and force execution
php artisan migrate --seed --force

# Process the Nginx configuration template
node /assets/scripts/prestart.mjs /assets/nginx.template.conf /nginx.conf

# Start PHP-FPM and Nginx in the background
php-fpm -y /assets/php-fpm.conf & 
nginx -c /nginx.conf
