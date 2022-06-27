# Setup for Symfony and Angular Dockerized Project
Docker compose for symfony + mysql + angular project

## Docker containers:

		DataBase:
		 1. MySQL
		 2. PhpMyAdmin
		
		Server Code:
		 1. PHP
		 2. Apache
	 
		 Front End Code:
		 1. NGINX


Usage
-----
Build
```bash
$ docker-compose build
```
Show all container
```bash
$ docker-compose ps
```
Connect to container
```bash
$ docker exec -it www_marvel bash
```
Install composer and create database
```bash
$ cd back/
$ composer install
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update -f
$ exit
```

Run development environment
```bash
$ docker-compose up
```
or run in background
```bash
$ docker-compose up -d
```


Access to projects
------------------
API: http://localhost:8741/api

Back: http://localhost:8741

Front: http://localhost:4200

Phpmyadmin: http://localhost:8080
