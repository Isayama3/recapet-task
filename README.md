Application Documentation

1. Prerequisites
    - Ensure you have the following installed on your system:
        - PHP: v8.1.1 or v8.2 (Compatible with this project)
        - Composer: for managing dependencies
        - MySQL (or your preferred database)

2. Project Setup Instructions
    Step 1: Clone the Repository
        - git clone <repository-url>
        - cd <project-directory>
        
    Step 2: Environment Configuration
        - Create Environment Files:
        - Copy the default .env file to create environment configurations for development and testing:
        - cp .env .env.test
        - Edit .env and .env.test with your specific database and application configuration settings. Ensure .env.test has settings for               your test database.
        - Run the following command to generate an application key:
        - php artisan key:generate
   
    Step 3: Install Dependencies
        - composer update
        - This will install all necessary dependencies specified in the composer.json file.

    Step 4: Database Setup
        - Run Migrations and Seeders:
        - Use the following command to set up your database with fresh migrations and seed data:
            - php artisan migrate:fresh --seed
        - This command will create all required tables and insert any necessary seed data.
        - Configure Database Connections:
            - Ensure that your .env file has the correct database credentials:
                - DB_CONNECTION=mysql
                - DB_HOST=127.0.0.1
                - DB_PORT=3306
                - DB_DATABASE=your_database
                - DB_USERNAME=your_username
                - DB_PASSWORD=your_password
    Step 5: Serving the Application
        - Start the development server with:
        - php artisan serve
        - The application should now be accessible at http://127.0.0.1:8000.
   
4. Postman API Access
    Step 1: Import Postman Collection
    - Import the Collection:
    - Open Postman and click on “Import” in the top-left corner.
    - Locate and import the Postman collection file from the project root. This will load all API endpoints into Postman.
    - Configure Postman Environment:
        - In Postman, set up a new environment and add the following variable:
            url: http://127.0.0.1:8000/api/v1/user/ (or your server URL if different)
    - Testing API Endpoints:
        - Select any endpoint in the imported collection and ensure the URL is set to use {{url}}.
        - Click “Send” to test the endpoint. Adjust any parameters as needed.
        - Authentication (If Required)
        - If the application requires authentication, first call the /api/login endpoint to retrieve an access token.
        - In each request, add an Authorization header with the format:
        - Bearer <access_token>
        - Accept application/json
5. Running Tests
    - Use the following command to run tests:
    - php artisan test
    - This command will run all unit and feature tests defined within the tests directory, using the .env.test configuration.
