# user-crud-raw
A version of user-crud using only raw PHP - no framework

## Installation
Requirements: docker-compose and composer.  
Steps:
* Pull code
* Composer install to generate autoload files
* Create .env file
* Run containers
```
git clone https://github.com/jonathanargo/user-crud-raw.git
cd user-crud-raw
composer install
cp .env.sample .env
docker-compose up -d
```
Make sure to enter your Google Maps API key to use address autofill.
