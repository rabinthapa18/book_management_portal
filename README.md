# Book Management application

This is a Book Management Application built with Laravel for the backend and Vue.js for the frontend.

## Running Locally

This project can be run locally in two modes:

1. Running the backend and frontend manually.
2. Using Docker Compose to run the entire application.

## Manual Setup

### Backend

The backend is built with Laravel and includes the following key components:

- **Laravel Framework**: A web application framework with expressive, elegant syntax.
- **Composer**: Dependency manager for PHP.

#### Prerequisite

1. [Composer](https://getcomposer.org/)
2. [PHP-pdo extention](https://www.php.net/manual/en/book.pdo.php)

#### Setup

1. Go to the backend folder and copy the example environment file and update the environment variables:

   ```sh
   cd backend
   cp .env.example .env
   ```

2. Install PHP dependencies:

   ```sh
   composer install
   ```

3. Run database migrations, seed it and start the server:

   ```sh
   php artisan migrate --seed
   php artisan serve --host=0.0.0.0 --port=8000

   ```

> **_NOTE:_** Initially, when setting up with default .env file, the backend will use SQLlite as database. You will be asked with a prompt to create a database.sqlite file. Please select "Yes" in that case.

### Frontend

The frontend is built with Vue.js and includes the following key components:

- **Vue.js**: A progressive JavaScript framework for building user interfaces.
- **Vite**: A build tool that aims to provide a faster and leaner development experience for modern web projects.

#### Prerequisite

1. [NodeJS (and npm)](https://nodejs.org/en)

#### Setup

1. Go to the backend folder and copy the example config file and update the variables:
   `sh
cd frontend
cp config-example.js config.js
`
   [//]: <> (Set the url for the backend)

2. Install dependencies:

   ```sh
   npm install
   ```

3. Start the development server:
   ```sh
   npm run dev -- --host
   ```

## Docker

The project includes Docker support for both the backend and frontend.

#### Prerequisite

1. [Docker](https://docs.docker.com/get-docker/)
2. [Docker Compose](https://docs.docker.com/compose/install/)

#### Composing the application and running it

To start the entire application using Docker Compose, run:

```sh
docker-compose up --build
```

## Testing

The project includes tests for the backend. To run the tests, start the backend server manually or using Docker Compose and run:

```sh
cd backend
php artisan test
```
