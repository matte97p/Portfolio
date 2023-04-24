<a id="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
        <img src="backend/storage/app/public/matte97.p.svg" alt="Logo" width="500" height="400">

  <h3 align="center">Portfolio</h3>
</div>

<!-- BUILT WITH -->

### Built With

- [![Angular][angular.io]][angular-docs]
- [![Laravel][laravel.com]][laravel-docs]

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->

## Getting Started

### Prerequisites

- [PostgreSQL][postgresql-download]
- [php8.2][php8.2-download]
  ```
  LINUX -> sudo apt install php8.0-fpm
  ```
- [composer][composer-download]

  ```
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"

  LINUX -> sudo apt install curl
          curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
  ```

- [Laravel10][laravel10-download]
  ```
  composer global require laravel/installer
  ```
- [Laravel Passport][laravel-passport-docs] -> provides a full OAuth2 server implementation
  ```
  composer require laravel/passport
  php artisan migrate
  php artisan passport:install --uuids
  php artisan passport:keys --force
  php artisan vendor:publish --tag=passport-config
  ```
- [GuzzleHttp][guzzlehttp-docs] -> manipulate the outgoing request or inspect the incoming response
  ```
  composer require guzzlehttp/guzzle
  ```
- [Spatie][spatie-docs] -> allows you to manage user permissions and roles in a database
  ```
  composer require spatie/laravel-permission
  php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
  php artisan migrate
  ```
- optional MAC [Mac Valet][laravel-valet-docs] -> blazing fast Laravel development environment that uses roughly 7 MB of RAM
  ```
  composer global require laravel/valet
  valet install
  cd ~/Sites
  valet park
  ```
- optional LINUX [Linux Valet][linux-valet-docs] -> Valet for Ubuntu is a port of the original made specifically for Ubuntu that attempts to mirror all the features of Valet v1

  ```
  - Ubuntu 14.04 or below
  sudo add-apt-repository -y ppa:nginx/stable
  sudo apt-get update

  - dependencies packages
  sudo apt-get install network-manager libnss3-tools jq xsel

  - Install PHP & its extensions
  sudo apt install software-properties-common
  sudo add-apt-repository ppa:ondrej/php
  sudo apt update
  sudo apt install php*-fpm
  sudo apt install php*-cli php*-curl php*-mbstring php*-mcrypt php*-xml php*-zip

  - MYSQL steps
  sudo apt-get -y install mysql-server
  sudo mysql_secure_installation
  sudo mysql
  mysql> ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
  mysql> FLUSH PRIVILEGES;
  mysql> exit;

  composer global require genesisweb/valet-linux-plus

  valet install
  cd ~/Sites
  valet park
  ```

- optional WINDOWS [Windows Valet][] -> Valet for Windows

  ```
  composer global require cretueusebiu/valet-windows
  valet install
  cd ~/Sites
  valet park
  ```

- optional [Laravel Redis][laravel-redis-docs] -> open source advanced key-value store.

  ```
  composer require predis/predis
  ```

- optional [Laravel Telescope][laravel-telescope-docs] -> Telescope provides insight into the requests coming into your application and more.
  ```
  composer require laravel/telescope
  php artisan telescope:install
  ```
- optional [Laravel Horizon][laravel-horizon-docs] -> dashboard Redis queues
  ```
  composer require laravel/horizon
  php
  ```
- [Reliese Laravel][reliese-laravel-docs] -> model generator from db
  ```
  composer require reliese/laravel
  php artisan vendor:publish --tag=reliese-models
  ```
- [Angular][angular-download]

  ```
  cd ./angular
  sudo -i npm install -g @angular/cli
  ```

- [Prime NG Angular][primeng-download]

  ```
  # with npm
  npm install primeng --save
  npm install primeng primeicons
  npm install @angular/animations@latest --save

  # with yarn
  yarn add primeng --save
  yarn add primeng primeicons
  ```

- [Angular Material][material-download]

  ```
  ng add @angular/material
  ```

### Installation

1. Clone the repo
   ```
   git clone https://github.com/matte97p/Portfolio.git
   ```
2. Install packages into cd project_dir
   ```
   /backend -> composer install
   /frontend -> npm install
   ```
3. Create DB and upload [DB Backup][]

   ```
   sudo -u postgres psql
   CREATE DATABASE portfolio;
   CREATE USER mario with PASSWORD 'rossi';
   ALTER USER mario WITH PASSWORD 'rossi'; --se già creato
   GRANT ALL PRIVILEGES ON DATABASE portfolio to mario;

   -- on db
   CREATE EXTENSION IF NOT EXISTS "uuid-ossp"; -- for uuid_generate_v4()

   php artisan migrate
   php artisan vendor:publish --tag=passport-migrations
   php artisan vendor:publish --tag=telescope-migrations
   ```

4. Create and start the web server for https://backend-portfolio.test/

   ```
   valet link backend-portfolio
   valet secure backend-portfolio
   ```

5. Run passport OAuth2 Client [read more][https://laravel.com/docs/10.x/passport]

   ```
   php artisan passport:client --password
   named LocalClient eg
   ```

6. Start the web server for http://localhost:8000/

   ```
   php artisan serve
   ```

7. Go to /angular Start the web server for http://localhost:4200/

   ```
   ng serve
   ```

8. Take a minute, go on your backend dir and do that before start programming
   ```
   php artisan inspire
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- Coding Guide Line -->

## Coding Guidelines

[PSR 12 DOCS][psr12-docs]
This section of the standard comprises what should be considered the standard coding elements that are required to ensure a high level of technical interoperability between shared PHP code.

[Angular coding style][angular-naming-docs]
Each guideline describes either a good or bad practice, and all have a consistent presentation.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->

<!-- LANGUAGES -->

[angular-docs]: https://angular.io/
[angular.io]: https://img.shields.io/badge/Angular-DD0031?style=for-the-badge&logo=angular&logoColor=white
[laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[laravel-docs]: https://laravel.com
[psr12-docs]: https://www.php-fig.org/psr/psr-12/
[angular-naming-docs]: https://angular.io/guide/styleguide

<!-- DOWNLOAD -->

[postgresql-download]: https://www.postgresql.org/download/
[php8.2-download]: https://www.php.net/downloads.php
[composer-download]: https://getcomposer.org/download/
[laravel10-download]: https://laravel.com/docs/10.x/installation
[primeng-download]: https://primeng.org/installation
[material-download]: https://material.angular.io/guide/getting-started

<!-- PACKAGES -->

[laravel-passport-docs]: https://laravel.com/docs/10.x/passport
[guzzlehttp-docs]: https://laravel.com/docs/10.x/http-client
[spatie-docs]: https://spatie.be/docs/laravel-permission/v5/basic-usage/basic-usage
[laravel-valet-docs]: https://laravel.com/docs/10.x/valet
[linux-valet-docs]: https://valetlinux.plus/
[laravel-redis-docs]: https://laravel.com/docs/10.x/redis
[laravel-telescope-docs]: https://laravel.com/docs/10.x/telescope
[laravel-horizon-docs]: https://laravel.com/docs/10.x/horizon
[reliese-laravel-docs]: https://github.com/reliese/laravel

<!-- UTILITIES -->

[laravel naming conventions]: https://webdevetc.com/blog/laravel-naming-conventions/
[php faker]: https://fakerphp.github.io/
