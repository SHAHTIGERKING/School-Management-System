# Advanced School Management System

A comprehensive, web-based School Management System built with PHP and MySQL. This system facilitates the management of students, teachers, classes, attendance, results, and marks.

## Structure
- `admin/`: Administrator Dashboard and management features.
- `public/`: Public facing pages (Home, Login, Register).
- `actions/`: PHP Logic for form handling.
- `config/`: Database configuration.
- `database/`: SQL schema files.
- `includes/`: Helper functions and shared components.
- `assets/`: CSS, JS, Images.
- `uploads/`: User uploaded files.

## Technologies
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 8+ (Procedural/PDO)
- **Database**: MySQL

## Setup Instructions
1.  **Configure Database**:
    -   Create a database named `school_db` in your MySQL server (via PHPMyAdmin or CLI).
    -   Import `database/schema.sql` to create tables and default data.
    -   Update `config/db.php` if your database credentials differ from default (root/empty).

2.  **Run Application**:
    -   Serve the project folder via XAMPP/WAMP (e.g., `http://localhost/school-management-system/`).

## Default Credentials
-   **Admin Email**: `shahzaibtigerking11@gmail.com`
-   **Password**: `shahtigerking29`
