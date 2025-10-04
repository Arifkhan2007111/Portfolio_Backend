# Use official PHP image with Apache
FROM php:8.2-apache

# Copy all files to the container
COPY . /var/www/html/

# Expose port 10000 for Render
EXPOSE 10000

# Change the Apache port to 10000 (Render needs it)
RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Start Apache server
CMD ["apache2-foreground"]
