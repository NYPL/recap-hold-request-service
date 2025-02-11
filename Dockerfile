FROM bref/php-83-fpm:latest

COPY --from=bref/extra-pgsql-php-83:1 /opt /opt

# Copy the source code in the image
COPY . /var/task

# Configure the handler file (the entrypoint that receives all HTTP requests)
CMD ["index.php"]
