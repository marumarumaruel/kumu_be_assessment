# Laravel Project

## Description

This is a Laravel-based web application for the BE Developer Assesment Exam.

## Prerequisites

Before running the project, ensure that you have the following installed:

- PHP >= 8.0
- Composer
- MySQL or any database you prefer
- Laravel >= 10.x
- Node.js and NPM (if using frontend dependencies)

## Installation

Follow these steps to set up the project locally:

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/your-laravel-project.git

2. Navigate to the project folder:

   cd your-laravel-project

3. Install the PHP dependencies using Composer:

   composer install

4. Set up your environment variables. Copy the .env.example file to .env or update the .nv file

5. Generate the application key:

   php artisan key:generate

6. Set up your database connection in the .env file. Edit the following fields:

   DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password

7. Run the migrations to create the database tables:

   php artisan migrate

8. (Optional) Seed the database with test data:

    php artisan db:seed

9. Install frontend dependencies (if your project uses them):

    npm install
    npm run build

10. Compile the assets:
    npm run dev

## Running the App

1. Go to <code>/register</code> to Register.
2. Login at <code>/login</code>.
3. Test API at the Dashbaord.