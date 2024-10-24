
---

# E-Commerce Website

## Overview
The E-Commerce Website project is a PHP and MySQL-based application that includes various functionalities such as item management, shopping carts, and social media features. This guide will walk you through how to set up and run the project using **XAMPP**.

## Requirements
- **XAMPP**: Install XAMPP, which includes Apache (for PHP) and MySQL (for the database).
- **PHP**: PHP 7 or later version.

## Installation Steps

### Step 1: Clone the Repository
Clone the repository to the XAMPP `htdocs` directory. You can clone the project by using the following command:
```
git clone https://github.com/rnzbobi/ITPROG.git
```

Alternatively, you can manually download the project and move it into your XAMPP `htdocs` folder.

### Step 2: Import the Database
1. Open **phpMyAdmin** by going to `http://localhost/phpmyadmin/` in your browser.
2. Create a new database (e.g., `itprog_db`).
3. Import the database SQL file:
    - Go to the **Import** tab in phpMyAdmin.
    - Select the file `sql/dbclothes.sql` and execute the import.

### Step 3: Configure the Project
1. Make sure your project is located in the `htdocs` folder under XAMPP. For example:
   ```
   C:\xampp\htdocs\ITPROG_VILORIA\ITPROG
   ```

2. Open `database.php` (or the appropriate configuration file) and set your MySQL credentials and database name:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "itprog_db";  // The database you created in phpMyAdmin
   ```

### Step 4: Run the Project
1. Start the Apache and MySQL services in XAMPP Control Panel.
2. Open your browser and navigate to:
   ```
   http://localhost/ITPROG_VILORIA/ITPROG/
   ```
   This should load the main page of your project.

### Folder Structure
- **html/**: This contains the PHP files for the web application.
- **sql/**: This contains the SQL file `dbclothes.sql`, which sets up the database.
- **screenshots/**: This folder contains screenshots demonstrating the interface.

### Sample Screenshots
Here are some sample screenshots of the project in action:

#### 1. Main Page
![Main Page](https://raw.githubusercontent.com/rnzbobi/ITPROG/main/screenshots/Main%20Page.png)

#### 2. Item Showcase
![Item Showcase](https://raw.githubusercontent.com/rnzbobi/ITPROG/main/screenshots/Item_showcase.png)

#### 3. Shopping Cart
![Shopping Cart](https://raw.githubusercontent.com/rnzbobi/ITPROG/main/screenshots/shopping_cart.png)

#### 4. Social Media Integration
![Social Media](https://raw.githubusercontent.com/rnzbobi/ITPROG/main/screenshots/social_media.png)

---
