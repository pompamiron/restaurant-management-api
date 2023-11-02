# Restaurant Management Web Application - Backend

This is the backend portion of a Restaurant Management Web Application. It provides the necessary APIs and functionalities for managing restaurant orders, menu items, kitchen monitoring, and more.

## Technologies Used

- Laravel: A PHP web application framework.
- MySQL: A relational database management system.
- Passport: For API authentication.

- The `app` directory contains the main application logic and controllers.
- The `database` directory contains migrations and seeders for setting up and populating the database.

## Setup

1. Clone the repository: `git clone <repository-url>`
2. Install Composer dependencies: `composer install`
3. Create a `.env` file by copying `.env.example` and configuring the database and other settings.
4. Generate an application key: `php artisan key:generate`
5. Run database migrations: `php artisan migrate`
6. Run database seeders: `php artisan db:seed`
7. Run create Encryption key: `php artisan passport:install`

## Authentication

To access most of the API endpoints, you need to be authenticated using JWT (JSON Web Token). The API provides two authentication-related endpoints:

### Login
- **Endpoint:** POST `/login`
- **Description:** Log in with your credentials to obtain an authentication token.
- **Parameters:** `email` and `password`
- **Response:** Returns a JWT token upon successful login.

### Logout
- **Endpoint:** POST `/logout`
- **Description:** Log out and invalidate the JWT token.
- **Authentication:** Requires an authenticated user with a valid token.

## Endpoints

The following endpoints are available for managing orders and menu items:

### Orders API

- **List Orders:** `GET /orders` - Retrieve a list of orders.
- **View Order:** `GET /orders/{id}` - Retrieve details of a specific order.
- **Create Order:** `POST /orders` - Create a new order.
- **Update Order:** `PUT /orders/{id}` - Update an existing order.

### Menu Items API

- **List Menu Items:** `GET /menu-items` - Retrieve a list of menu items.

## Postman Tests

To facilitate testing the API, you can use Postman, a popular API testing tool. We have created a collection of Postman tests to help you interact with the API easily. You can import the collection by clicking [here](https://elements.getpostman.com/redirect?entityId=30900055-30e9e686-f0f2-47fb-926e-b20addc0e6dd&entityType=collection).
