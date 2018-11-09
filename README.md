# Siyakha Practical Test

This application was developed with [CakePHP](http://cakephp.org) 3.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

Install GIT 

Run the following GIT command to clone the project repository:

``` bash

$ cd /path/to/app

$ git clone https://github.com/reggiestain/siyahka-test.git

```

Alternately download zip project folder and extract into the "www" directory on your apache server.

## Run Migration
By default Migrations is installed with the default application skeleton.

A CakePHP application contains src/Command, src/Shell and src/Shell/Task directories that contain its shells and tasks. It also comes with an executable in the bin directory:

Run database migrations and seeder below:
``` bash
$ cd /path/to/app

$ bin/cake migrations migrate

$ bin/cake migrations seed
```

Alternately the application database file is located in "config/schema/siyakha_test_db.sql" directory of the project folder.
Run this sql script on your mysql server to create the "siyakha_test_db" database.

## Configuration

Read and edit `config/app.php` and setup the 'Datasources' and any other
configuration relevant for your application.

You are now set to go.

Run the  application on your web server and register to login with email and password




