<a name="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/JosephTaverniti/SSM_LARAVEL_BackEnd">
    <img src="storage/app/public/GitHub-logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">Libretto</h3>
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li><a href="#built-with">Built With</a></li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#coding-guidelines">Coding Guidelines</a></li>
  </ol>
</details>

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
  ```sh
  LINUX -> sudo apt install php8.0-fpm
  ```
- [composer][composer-download]

  ```sh
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"

  LINUX -> sudo apt install curl
          curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
  ```

- [Laravel10][laravel10-download]
  ```sh
  composer global require laravel/installer
  ```
- [Laravel Passport][laravel-passport-docs] -> provides a full OAuth2 server implementation
  ```sh
  composer require laravel/passport
  php artisan migrate
  php artisan passport:install --uuids
  php artisan passport:keys --force
  php artisan vendor:publish --tag=passport-config
  ```
- optional MAC [Mac Valet][laravel-valet-docs] -> blazing fast Laravel development environment that uses roughly 7 MB of RAM
  ```sh
  composer global require laravel/valet
  valet install
  cd ~/Sites
  valet park
  ```
- optional LINUX [Linux Valet][linux-valet-docs] -> Valet for Ubuntu is a port of the original made specifically for Ubuntu that attempts to mirror all the features of Valet v1

  ```sh
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

- optional WINDOWS [Linux Valet][] -> Valet for Windows
  ```sh
  composer global require cretueusebiu/valet-windows
  valet install
  cd ~/Sites
  valet park
  ```
- optional [Laravel Telescope][laravel-telescope-docs] -> Telescope provides insight into the requests coming into your application and more.
  ```sh
  composer require laravel/telescope
  php artisan telescope:install
  ```
- optional [Laravel Horizon][laravel-horizon-docs] -> dashboard Redis queues
  ```sh
  composer require laravel/horizon
  php
  ```
- [Angular][angular-download]

  ```sh
  cd ./angular
  sudo -i npm install -g @angular/cli
  ```

- [Prime NG Angular][primeng-download]

  ```sh
  # with npm
  npm install primeng --save
  npm install primeng primeicons
  npm install @angular/animations@latest --save

  # with yarn
  yarn add primeng --save
  yarn add primeng primeicons
  ```

  ```

  ```

- [Angular Material][material-download]

  ```sh
  ng add @angular/material
  ```

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/matte97p/Portfolio.git
   ```
2. Install packages into cd project_dir
   ```sh
   /backend -> composer install
   /frontend -> npm install
   ```
3. Create DB and upload [DB Backup][]

   ```sh
   sudo -u postgres psql
   CREATE DATABASE portfolio;
   CREATE USER mario with PASSWORD 'rossi';
   GRANT ALL PRIVILEGES ON DATABASE portfolio to mario;

   -- on db
   CREATE EXTENSION IF NOT EXISTS "uuid-ossp"; -- for uuid_generate_v4()

   php artisan migrate
   php artisan vendor:publish --tag=passport-migrations
   php artisan vendor:publish --tag=telescope-migrations
   ```

4. Create and start the web server for https://backend-portfolio.test/

   ```sh
   valet link backend-portfolio
   valet secure backend-portfolio
   ```

5. Run passport OAuth2 Client [read more][https://laravel.com/docs/10.x/passport]

   ```
   php artisan passport:client --password
   named LocalPswClient eg
   ```

6. Start the web server for http://localhost:8000/

   ```
   php artisan serve
   ```

7. Go to /angular Start the web server for http://localhost:4200/

   ```
   ng serve
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- Coding Guide Line -->

## Coding Guidelines

[PSR 12 DOCS][psr12-docs]
This section of the standard comprises what should be considered the standard coding elements that are required to ensure a high level of technical interoperability between shared PHP code.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->

<!-- LANGUAGES -->

[angular-docs]: https://angular.io/
[angular.io]: https://img.shields.io/badge/Angular-DD0031?style=for-the-badge&logo=angular&logoColor=white
[laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[laravel-docs]: https://laravel.com
[psr12-docs]: https://www.php-fig.org/psr/psr-12/

<!-- DOWNLOAD -->

[postgresql-download]: https://www.postgresql.org/download/
[php8.2-download]: https://www.php.net/downloads.php
[composer-download]: https://getcomposer.org/download/
[laravel10-download]: https://laravel.com/docs/10.x/installation
[primeng-download]: https://primeng.org/installation
[material-download]: https://material.angular.io/guide/getting-started

<!-- PACKAGES -->

[laravel-passport-docs]: https://laravel.com/docs/10.x/passport
[laravel-valet-docs]: https://laravel.com/docs/10.x/valet
[linux-valet-docs]: https://valetlinux.plus/
[laravel-telescope-docs]: https://laravel.com/docs/10.x/telescope
[laravel-horizon-docs]: https://laravel.com/docs/10.x/horizon

<!-- UTILITIES -->
