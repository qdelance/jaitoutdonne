jaitoutdonne
============

A simple VDM clone :)

# Requirements
  * PHP, FPM suggested
  * Apache or NGinx
  * MySQL/MariaDB

Symfony app is supposed to be clone in /var/www/<sf_app>

# PHP configuration

Ensure php-fpm package is installed.
Use either a TCP/IP socket or UNIX socket (listen key in default pool www.conf), this will impact web server configuration.
Note that conf with Apache is way simpler with TCP/IP socket.

# Web server configuration

## Apache

Refer to http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html#using-mod-proxy-fcgi-with-apache-2-4

Enable module proxy_fastcgi (to communicate with PHP-FPM).
Ensure module rewrite is enabled (to get clean URLs).

## NGinx
   
Refer to http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html#nginx

# Symfony application   
    
Change rights: https://symfony.com/doc/current/book/installation.html#book-installation-permissions

Install vendors:
    
    $ composer install
    
Adjust DB settings accordingly.

Create DB + schema:

    $ ./bin/console doctrine:database:create
    $ ./bin/console doctrine:schema:update --force
    
Load fixtures (init data in DB) : 

    $ ./bin/console doctrine:fixtures:load

There are admin/password and user/password users ready to use.
To create new users:

    $ ./bin/console app:add-user