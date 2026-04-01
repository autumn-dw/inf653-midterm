# INF 653 Midterm Project
By Autumn Wertz
Link to Render: https://inf653-midterm-444d.onrender.com/

## Project Overview
This project consists of a RESTful API built with **PHP** and **PostgreSQL**. It manages a relational database of quotes, authors, and categories. The application is designed to handle standard CRUD operations through JSON-based endpoints and is hosted on the **Render** cloud platform.

## Technical Architecture
The system utilizes a Model-View-Controller (MVC) inspired directory structure to separate database logic from API routing:

* **`models/`**: Contains PHP classes (`Quote.php`, `Author.php`, `Category.php`) utilizing **PDO** for secure database interactions and prepared statements.
* **`config/`**: Contains the `Database.php` class for managing PostgreSQL connections.
* **`root/`**: Contains the entry point `index.php` and the individual API directories for routing.
* **Environment**: Development was conducted within a **Dockerized** Apache/PHP environment, with system dependencies managed via **NixOS**.

## API Documentation

### Quotes
* **`GET /quotes/`**: Returns a JSON array of all quotes, including the associated author name and category name.
* **`GET /quotes/?id={id}`**: Returns a single quote object matching the specified ID.
* **`GET /quotes/?random=true`**: **(Extra Credit)** Returns a single random quote from the database.
* **`POST /quotes/`**: Creates a new quote. Requires `quote`, `author_id`, and `category_id`.
* **`PUT /quotes/`**: Updates an existing quote. Requires `id`, `quote`, `author_id`, and `category_id`.
* **`DELETE /quotes/`**: Deletes a quote. Requires `id`.

### Authors
* **`GET /authors/`**: Returns a JSON array of all authors.
* **`GET /authors/?id={id}`**: Returns a single author matching the specified ID.
* **`POST /authors/`**: Creates a new author. Requires `author`.
* **`PUT /authors/`**: Updates an existing author. Requires `id` and `author`.
* **`DELETE /authors/`**: Deletes an author. Requires `id`.

### Categories
* **`GET /categories/`**: Returns a JSON array of all categories.
* **`GET /categories/?id={id}`**: Returns a single category matching the specified ID.
* **`POST /categories/`**: Creates a new category. Requires `category`.
* **`PUT /categories/`**: Updates an existing category. Requires `id` and `category`.
* **`DELETE /categories/`**: Deletes a category. Requires `id`.

## Database Schema
The PostgreSQL database consists of three primary tables:

1.  **`authors`**: `id` (Serial PK), `author` (VARCHAR)
2.  **`categories`**: `id` (Serial PK), `category` (VARCHAR)
3.  **`quotes`**: `id` (Serial PK), `quote` (TEXT), `author_id` (FK), `category_id` (FK)

## Disclosure
This API is hosted on a Render Free Tier instance. If the service has been inactive, the initial request may experience a delay of thirty seconds while the container instances spin up.
