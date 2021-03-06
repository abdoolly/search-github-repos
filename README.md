# Github Search Repos

This is a lumen project that gives the consumer
an api to be able search github repos
having the ability to get top 10,50 or whatever he wants
he can also filter by date or programming language.

## Pre Requisites

- php 7.3 or above
- composer 2.0
- phpunit for testing

or just install 

- docker

## Configuration

make sure you copy the file `.env.example`
and rename it to `.env` no special config required in this step so, just leave it as it is.

## Installation

run the dependency installation command

```
composer install
```

this will create a folder called vendor with all the dependencies the project need to start

or build using docker
```
docker build --target run -t search-repo .
```

## Start the backend

To start the backend run the following command

```
php -S localhost:8000 -t public
``` 

or use docker to run it but, make sure you have ran the docker build command above

```
docker run -d -p 8000:8000 search-repo
```

This will start the server on the following link [http://localhost:8000](http://localhost:8000)

## Run tests

To be able to run the tests open your terminal and make sure you
are on the root folder of the project and run

```
phpunit
```

or you can configure phpunit in Phpstorm then open the tests folder
and select the GithubTest.php which has all the tests.

### using docker
or you can use docker to run the tests using the test stage

this will run two commands the build choosing the target test 
then run it to run the tests.

```
docker build --target test -t search-repo .

docker run search-repo
```

## API Docs

There is only one API available to use which serve all your needs in this project.

```
GET /search/repos
```

### available query parameters to pass

Parameter Name | type | mandatory | example | description | 
--- | --- |--- | --- |--- | 
top | integer | false | ?top=10 |this parameter how many results come back so top=10 will return 10 repositories |
langs | string comma separated | false | ?langs=javascript,typescript or langs=java |this is a string comma separated for multiple languages and it filters the incoming repositories by the programming language |
searchTerm | string | true if langs exist and no date specified | ?searchTerm=express |this paramters makes sure that the repo name include that search term and it's required if the langs field is specified and the date field is not otherwise it's optional|
date | date | false | ?date=2020-05-01 | this accepts any standard day format it get all repos that it's created_at is after that date |

All parameters above can be made in one query like that for example

```
/search/repos?top=10&langs=javascript,typescript&date=2020-01-01&searchTerm=laravel
```

The above query will get the top 10 repos sorted by highest stars count that only written in javascript and typescript with date after 2020-01-01 and it's name include the word "laravel".
