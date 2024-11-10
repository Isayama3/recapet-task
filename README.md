Setup Instructions

Create Environment Files: Copy the .env file to .env.test and configure your test database settings.

Update Composer: Run the following command to update dependencies: composer update

Run Migrations: Set up your database by running: php artisan migrate:fresh

Serve the Application: Start the development server with: php artisan serve

Access the Application: Open your browser and navigate to http://127.0.0.1:8000/products to view the products

Run Tests: To execute the test cases, use the command: php artisan test

Cronjob Setup To automate the fetching of product data from the external API, add the following cronjob to your server: php artisan app:update-products-data-in-d-b
