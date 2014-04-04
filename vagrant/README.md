# Description
This configuration includes following software:

* PHP 5.4.*
* MySQL 5.5
* GIT
* Apache
* Vim
* Curl
* Composer
* PHPUnit 3.7.30
* NodeJS
* Bower

# Usage

First you need to create the redkite VM using vagrant:
```Bash
$ cd vagrant
$ vagrant up
```

While waiting for Vagrant to build and start the VM, you should add an entry into /etc/hosts file on the host machine.
```
10.0.10.200      redkite.dev
```

From now on you should be able to access your Redkite project at [http://redkite.dev/](http://redkite.dev/).

## Remark

Please be aware that the create VM will not install the Redkite dependencies or setup the enviroment configuration for you.
To do this please ssh into the VM (using `vagrant ssh`) and do the following:
```Bash
cd /var/www/redkite/
composer install
php app/console redkitecms:configure
php app/rkconsole redkitecms:install
```

The database (mysql) configuration is:
- host: localhost
- port: 3306
- user: root
- password:
