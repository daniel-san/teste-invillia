# What is this repo?
This repo contains the code for the senior developer test from Invillia.

Below will be instructions on how to install and run the application, as well as other useful information.

# Technologies used
The following technologies were used during development:
- Laravel
- MySql
- Redis


# Installation
After cloning the project, install the project dependencies with composer:
```
composer install
```

## Configuring the environment
After the dependencies installation is finished, create a new ```.env``` file by copying the ```.env.example``` file, and then generating the application key:
```
cp .env.example .env
php artisan key:generate
```

Inside the ```.env``` file you can place the needed info for the Database connection, as well as other information that might be needed:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=teste_invillia
DB_USERNAME=sail
DB_PASSWORD=password
```
The info above is what was used during the development. If you're not running the application from inside a ```docker-compose``` network, variables like ```DB_HOST``` should be changed to the actual address of the database, queue, etc..

## Docker containers and Laravel Sail
The main ```docker-compose.yml``` file is in the root of the project.
[Laravel Sail](https://github.com/laravel/sail) was used to run the containers needed for the application. It acts mostly as a helper to run commands inside the containers created by docker. For example, you can run artisan commands inside the main application container like this:
```
./vendor/bin/sail artisan migrate
```

In the end it proxies ```docker-compose``` commands to the ```docker-compose``` binary, so this:
```
#alias sail="./vendor/bin/sail"
sail up -d
```
is the same as running this:
```
docker-compose up -d
```

For convenience, throughout this document ```sail``` will be used when running commands like ```artisan migrate```, but the ```docker-compose``` counterparts can be run without any issues.
It's recomended to alias the sail script to easily run commands:
```
alias sail='bash vendor/bin/sail'
```

## Starting the application 
The application can be started using ```sail``` by running the command:
```
sail up laravel.test mysql redis
or
sail up -d laravel.test mysql redis
```
The first execution of this command will take some time while docker builds the containers for the web application, mysql and redis.

```laravel.test``` is the name of the service created inside the ```docker-compose.yml``` file.
This name can be configured, along with other values that are used during the creation of the containers when using ```sail```, inside the ```.env``` file:
```
APP_PORT=80
APP_SERVICE="laravel.test"
DB_PORT=3306
WWWUSER=
WWWGROUP=
```
The ```APP_SERVICE``` variable defines which container/service is the one running the application, and '```laravel.test``` is the default value if not defined.

### Running ```sail``` as root with ```sudo```
If your main user doesn't have permissions to run docker without ```sudo```, running ```sail``` commands as root might cause issues with the application container related to folder permissions. In this case, you should overwrite the ```WWWUSER``` and ```WWWGROUP``` variables inside the ```.env``` file, like this:
```
WWWUSER=1000 #run 'echo $UID' to get your user id
WWWGROUP=1000 #run 'id -g' to get your user group id
```
After this rebuild the containers, if they were already built:
```
sudo ./vendor/bin/sail build laravel.test mysql redis
```
Now the application should run without any issues. 

## Migrating the database
Resuming the installation, migrate the database:
```
sail artisan migrate
```

## Starting the queue worker
For the feature of proccessing the xml files in the background to work, the queue worker needs to be started:
```
sail artisan queue:work
```

# Using the application
The main page of the application is the [root page](http://localhost), where a form is displayed allowing the upload of 2 XML files: 1 for people and 1 for ship orders.
The files uploaded go through the following validations:
  - Validate that the XML file is not malformed;
  - Validate that data inside the file is valid;
    - For example, the phone number field of the people XML file must be numeric. Otherwise the file is invalid;

If both files are valid, then they are processed and the results stored into the database.
If the "Run in the background" option is checked, the files are proccessed in a queue.

## The API
After processing the files, the data processed can be accessed through the following api routes:
  - ```/api/people``` - This route returns all the people as json;
  - ```/api/ship-orders``` - This route returns all the ship orders as json;

The access to these endpoints require an api token of the ```Bearer``` type to be sent in the header of the request. Example with ```curl```:
```
curl -i http://localhost/api/people \
  -H "Authorization: Bearer <token>"
```

### Generating a token
A user can generate a token through the browser after user registration through the route [```/register```](http://localhost/register) and then generating a token by clicking at the menu and selecting the "[API Tokens](http://localhost/user/api-tokens)" option.

Users can also register and login through the API using the following routes:
  - ```/api/register``` - This route registers the user using its name, email and password;
  - ```/api/login``` - This route authenticates the user in using its email and password, and returns an api token in the response;

# API documentation
The project includes an API documentation using the OpenAPI spec. The files containing the documentation are ```apidoc.yaml``` and ```apidoc.json```.

The following command creates a docker container that can be used to view the documentation in the browser:
```
docker run --rm  -p 8080:8080 -e SWAGGER_JSON=/doc/apidoc.json -v $PWD:/doc swaggerapi/swagger-ui
```
The command must be run at the project folder root. Then the documentation can be viewed in the browser at [http://localhost:8080](http://localhost:8080).

# Automated tests
The automated tests can be ran with the following command:
```
sail artisan test
```
