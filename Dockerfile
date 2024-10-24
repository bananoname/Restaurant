# Use the official PHP 7.4 image with Apache
FROM php:7.4-apache

# Copy the entire application code to the container
COPY challenge /var/www/html/

# Randomize the flag name and move it to the root directory
COPY flag.txt /flag.txt
RUN bash -c 'FLAG_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 12) && cp /flag.txt "/${FLAG_NAME}_flag.txt" && rm /flag.txt'

# Change ownership of the application files to the www-data user
RUN chown -R www-data:www-data /var/www/html

# Switch to the www-data user
USER www-data

# Expose port 80 to allow external access
EXPOSE 80
