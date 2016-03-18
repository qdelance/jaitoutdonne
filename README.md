jaitoutdonne
============

A simple VDM clone :)

Add vhost:
    
    TODO
    
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

    $ (TODO, copy from demo) ./bin/console XXX:add-user