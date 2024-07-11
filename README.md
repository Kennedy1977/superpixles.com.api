
# Superpixles API

This project is a RESTful API built using the Slim Framework. It provides endpoints to manage users.

## Requirements

- PHP 7.3 or higher
- Composer
- MySQL

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Kennedy1977/superpixles.com.api.git
   cd superpixles.com.api
   ```

2. **Install dependencies:**

   ```bash
   composer install
   ```

3. **Set up the environment variables:**

   Create a `.env` file in the project root and add your database credentials:

   ```env
   DB_HOST={hostname}
   DB_NAME={database}
   DB_USER={username}
   DB_PASS={password}
   ```

4. **Set up the database:**

   Connect to your MySQL database and create the `users` table:

   ```sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL UNIQUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com');
   INSERT INTO users (name, email) VALUES ('Jane Doe', 'jane@example.com');
   ```

## Running the Project

1. **Start the PHP built-in server:**

   ```bash
   php -S localhost:8080 -t public
   ```

2. **Access the API:**

   - List all users: `GET /api/users`

     ```bash
     curl -i -H "Authorization: Bearer your-secret-token" http://localhost:8080/api/users
     ```

   - Get a specific user by ID: `GET /api/user/{id}`

     ```bash
     curl -i -H "Authorization: Bearer your-secret-token" http://localhost:8080/api/user/1
     ```

## Error Handling

A custom error handler is set up to display a friendly message when a non-existent route is accessed.

- Visiting a non-existent route like `http://localhost:8080/some-non-existent-route` will display the message: "Oh no! page not found!".

## Middleware

A simple token-based authentication middleware is included. Requests must include a valid `Authorization` header:

```bash
curl -i -H "Authorization: Bearer your-secret-token" http://localhost:8080/api/users
```

## License

This project is licensed under the MIT License.
