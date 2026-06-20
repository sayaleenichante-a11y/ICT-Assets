# Day 1 Development Report - AMS

## Project Overview
The **ICT Asset Management System (AMS)** is a web-based application designed to track and manage ICT assets, vendors, and locations. The system is built using **PHP**, **MySQL**, and **Bootstrap 5**.

---

## 1. Database Schema (`ict_assets_management.sql`)
The database `ict_asset_management` has been designed with the following core tables:

### Master Tables
- **`asset_categories`**: Stores categories of assets (e.g., Laptops, Printers).
- **`vendors`**: Manages vendor details including contact info.
- **`locations`**: Tracks physical locations (Building, Floor) where assets are deployed.
- **`asset_status`**: Defines the lifecycle status of an asset (e.g., Active, Retired, In Repair).

### User Management
- **`users`**: Stores user credentials for authentication.

---

## 2. Backend (PHP) Components

### Configuration
- **`config/db.php`**: Establishes the connection to the MySQL database using `mysqli`.
  - Host: `localhost`
  - User: `root`
  - Database: `ict_asset_management`

### Authentication Module (`auth/`)
- **`login.php`**: Handles user login.
  - Validates email and password against the `users` table.
  - Starts a session upon successful login.
  - Redirects to the dashboard (`index.php`).
- **`logout.php`**: Destroys the session and redirects to the login page.

### Core Structure (`includes/`)
- **`header.php`**: Contains the HTML `<head>`, Bootstrap CSS link, and the top navigation bar.
- **`sidebar.php`**: Implements the side navigation menu with links to:
  - Dashboard
  - Assets
  - Vendors
  - Categories
  - Locations
  - Logout
- **`footer.php`**: Closes the main content div and includes the footer section.

### Dashboard (`index.php`)
- The main entry point for authenticated users.
- Checks for an active session (`user_id`). If not found, redirects to `auth/login.php`.
- Includes the header, sidebar, and footer components to form the layout.

---

## 3. Frontend (Bootstrap 5)
- The application uses **Bootstrap 5.3.2** for styling and responsiveness.
- **Layout**:
  - A responsive grid system is used.
  - **Sidebar**: Fixed width (`col-md-2`), light background.
  - **Main Content**: Takes up the remaining space (`col-md-10`).
- **Forms**: Styled with Bootstrap form controls (`form-control`, `btn-dark`).

---

## 4. Bug Fixes & Improvements (Day 1)

### Authentication Fixes
1.  **Form Input Mismatch**: Fixed `login.php` to look for `$_POST['email']` instead of `username`.
2.  **Redirection Path**: Corrected the redirect after login to point to `../index.php`.
3.  **Session Loop**: Updated `index.php` to check for `$_SESSION['user_id']` instead of `username` to prevent infinite redirects.
4.  **Database Connection**: Ensured `db.php` is correctly included in `login.php`.

---

## Next Steps
- Implement CRUD operations for Assets, Vendors, and Categories.
- Enhance the Dashboard with summary statistics.
- Implement role-based access control (if required).
