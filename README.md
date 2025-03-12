# Company-X Warehouse Management System Practical Interview

## Introduction

This project is a warehouse management system developed using Laravel. It includes modules for managing pallets, packages, quality marks, and more.


### Prerequisites

Ensure you have the following installed on your system:

- PHP >= 8.0
- Composer
- Laravel >= 10.x
- MySQL or any other supported database
- Node.js and npm (for frontend assets)

### Installation

1. **Clone the Repository**

   Clone the repository to your local machine using the following command:
  
   git clone https://github.com/SamuelMakoholi/warehouse-practical.git
   cd company-x

2. **Set Up Environment Variables**

   Copy the example environment file and generate an application key:

   cp .env.example .env
   php artisan key:generate
   
 

3. **Install Dependencies**

   Install the necessary dependencies using Composer and npm:

   
   composer install
   npm install
   npm run dev
   npm run build
   

4. **Set Up the Database**

   Run the following commands to migrate and seed the database:

   php artisan migrate
   php artisan db:seed
   
   In this step, you prepare the database by running migrations and seeding it with initial data. `php artisan migrate` applies the database schema, and `php artisan db:seed` populates the database with sample data.

5. **Run the Application**

   Start the development server:


   php artisan serve
   
   This step starts the Laravel development server, allowing you to access the application in your web browser. The `php artisan serve` command runs the server on `http://localhost:8000`.

6. **Verify Functionality**

   Open your browser and navigate to `http://localhost:8000` to test the application. Log in using the credentials `admin@gmail.com` / `00000000`, or you can create an account and log in.

7. **Support**
   If you encounter any difficulties during the installation process or have further questions, you can reach out for support through the following channels:

   Email: samuelmakoholi@gmail.com Phone: 0776675208 Additional Considerations