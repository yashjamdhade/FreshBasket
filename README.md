# 🛒 FreshBasket -- Online Grocery Delivery System

FreshBasket is a simple **online grocery/fruit ordering web
application** developed using **HTML, CSS, PHP and MySQL**. It allows
customers to select multiple products, choose quantities, enter delivery
details, place an order, and view/search stored orders.

The project is designed as a beginner-friendly **PHP + MySQL
CRUD/database application** and can be hosted on platforms such as
InfinityFree.

## 🌐 Live Website

**FreshBasket:** https://freshbasket.infinityfreeapp.com/

> The live hosting URL may be unavailable temporarily depending on the
> hosting server.

------------------------------------------------------------------------

## ✨ Features

-   🏠 Simple and responsive home/order page
-   🍎 Multiple fruit/product selection
-   🔢 Quantity selection for each product
-   💰 Automatic order-total calculation
-   📋 Customer delivery information form
-   💾 Stores orders in a MySQL database
-   ✅ Order-success confirmation with Order ID
-   📦 Displays saved orders in a table
-   🔎 Search orders by customer, email, city or product
-   🏷️ Filter orders by status
-   🔐 Prepared SQL statements for database operations
-   🛡️ Input validation and safe HTML output
-   📱 Responsive design for desktop and mobile screens

------------------------------------------------------------------------

## 🧰 Technology Stack

  Technology     Purpose
  -------------- ------------------------------------------------
  HTML5          Website structure and order form
  CSS3           Styling, layout and responsive design
  JavaScript     Product selection and total calculation
  PHP            Server-side processing and database operations
  MySQL          Storing customer and order information
  MySQLi         PHP-MySQL database connection
  InfinityFree   Web hosting
  phpMyAdmin     Database management

------------------------------------------------------------------------

## 📁 Project Structure

``` text
FreshBasket/
│
├── index.html          # Main grocery ordering page
├── connect.php         # MySQL database connection
├── save.php            # Validates and saves orders
├── view.php            # Displays, searches and filters orders
└── freshbasket.sql     # Database table and sample data
```

------------------------------------------------------------------------

## 🛍️ Available Products

  Product                     Price
  ------------------- -------------
  🍎 Apple                ₹150 / kg
  🍌 Banana             ₹60 / dozen
  🥭 Alphonso Mango       ₹180 / kg
  🍊 Orange               ₹100 / kg
  🍇 Green Grapes         ₹120 / kg
  🍉 Watermelon            ₹40 / kg

The total is calculated based on the selected products and their
quantities.

------------------------------------------------------------------------

## 🔄 Application Flow

``` text
Customer opens website
        ↓
Selects one or more fruits
        ↓
Chooses quantities
        ↓
Total amount is calculated
        ↓
Enters delivery details
        ↓
Submits the order
        ↓
PHP validates the data
        ↓
Order is inserted into MySQL
        ↓
Success confirmation is displayed
        ↓
Orders can be viewed/search/filtered
```

------------------------------------------------------------------------

## 🗄️ Database

The project uses a MySQL table named `orders`.

### `orders` table

  Column           Type            Description
  ---------------- --------------- ----------------------------------
  id               INT UNSIGNED    Primary key, auto increment
  customer_name    VARCHAR(100)    Customer name
  email            VARCHAR(150)    Customer email
  phone            VARCHAR(20)     Customer phone
  address          VARCHAR(255)    Delivery address
  city             VARCHAR(100)    Delivery city
  state            VARCHAR(100)    Delivery state
  pincode          VARCHAR(10)     Postal code
  products         VARCHAR(500)    Selected products and quantities
  total_amount     DECIMAL(10,2)   Order total
  payment_method   VARCHAR(50)     Payment method
  status           VARCHAR(30)     Order status
  created_at       TIMESTAMP       Order creation date/time

The database schema and sample records are included in
`freshbasket.sql`.

------------------------------------------------------------------------

## 🔐 Security

FreshBasket includes basic security and validation measures:

-   Server-side validation of customer details
-   Email, phone and PIN code validation
-   Whitelist validation for products
-   Server-side price calculation
-   Prepared statements for `INSERT` and search/filter queries
-   HTML escaping using `htmlspecialchars()`
-   UTF-8 (`utf8mb4`) database connection
-   Validation of allowed states, payment methods and order statuses

### Example security test

The application is designed to safely handle a harmless input such as:

``` text
Customer Name: O'Reilly
```

The apostrophe should be stored and displayed without causing an SQL
error.

------------------------------------------------------------------------

## 🧪 Test Cases

The project includes screenshots demonstrating the following test cases:

  Test Case   Description
  ----------- --------------------------------------------------------------
  1           Home page opens successfully
  2           Multiple products and quantities can be selected
  3           Valid order is successfully inserted into MySQL
  4           Saved orders are displayed
  5           Search/filter functionality works
  6           Special-character input such as `O'Reilly` is handled safely
  7           Hosted application can be opened using the final URL

### Test Case Screenshots

Place the supplied screenshot files in a folder named `screenshots/` in
your GitHub repository:

``` text
screenshots/
├── 01_home_page.png
├── 02_multiple_product_selection.png
├── 03_successful_insert.png
├── 04_record_display.png
├── 05_search_filter.png
├── 06_security_test.png
└── 07_hosted_application.png
```

## 🚀 How to Run Locally

### 1. Install a PHP/MySQL environment

You can use:

-   XAMPP
-   WAMP
-   Laragon

### 2. Copy the project

Place the `FreshBasket` folder inside your web server directory.

For XAMPP:

``` text
C:\xampp\htdocs\FreshBasket
```

### 3. Create the database

Open phpMyAdmin and create a database, for example:

``` text
freshbasket
```

Then import:

``` text
freshbasket.sql
```

### 4. Configure the database connection

Update `connect.php` with your local MySQL credentials:

``` php
$host = "localhost";
$user = "root";
$password = "";
$database = "freshbasket";
```

### 5. Start the server

Start:

-   Apache
-   MySQL

Then open:

``` text
http://localhost/FreshBasket/
```

To view saved orders:

``` text
http://localhost/FreshBasket/view.php
```

------------------------------------------------------------------------

## ☁️ InfinityFree Deployment

For deployment on InfinityFree:

1.  Create an InfinityFree hosting account.
2.  Create a MySQL database.
3.  Import `freshbasket.sql` using phpMyAdmin.
4.  Upload the PHP/HTML files to the website's `htdocs` directory.
5.  Update `connect.php` with the hosting database host, username,
    password and database name.
6.  Open the assigned domain/subdomain in a browser.
7.  Test order insertion and the `view.php` page.

> **Security:** Never commit real database passwords, API keys or other
> secrets to GitHub. Use placeholders in the public repository and
> configure production credentials separately.

------------------------------------------------------------------------

## 📸 Project Screenshots

The repository can include the provided test-case screenshot ZIP:

**`freshbasket_test_case_screenshots.zip`**

It contains all seven screenshots used for the project's testing and
documentation.

------------------------------------------------------------------------

## 🎓 Academic Project

This project demonstrates:

-   Front-end form development
-   Client-side validation and calculations
-   PHP form processing
-   MySQL database connectivity
-   CRUD/database operations
-   Search and filtering
-   SQL injection protection using prepared statements
-   Basic input/output security
-   Web hosting and deployment

