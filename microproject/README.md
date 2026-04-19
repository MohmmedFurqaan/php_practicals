# Employee Management System (EMS) Microproject

## Project Overview
The Employee Management System (EMS) is a lightweight web application built using core PHP, MySQL, and Bootstrap 5. It is designed to manage employee records, track daily attendance, and provide role-based dashboards. 

## Features
* **Role-Based Access Control**: Separate views and permissions for `Admin` and `Employee`.
* **Employee Management**: Full CRUD operations (Add, Edit, List, and Delete employees).
* **Attendance Tracking**: Admins can mark daily attendance (Present, Absent, Leave) which employees can view on their personal dashboards.
* **Secure Authentication**: Hashed passwords (BCRYPT) and robust PHP session-based login.

---

## Architecture & Data Flow

### How Data is Passed
Data in this project is handled securely using native PHP features and PDO (PHP Data Objects):
1. **Forms & User Input**: Data entered into forms (e.g., adding an employee in `modules/employee/add.php` or marking attendance in `modules/attendance/mark.php`) is passed to the server via **HTTP POST requests** (`$_POST`).
2. **URL Parameters**: Identifying specific records (like editing or deleting a specific employee, or changing the date for attendance) utilizes **HTTP GET requests** passing IDs in the URL parameters (`$_GET`).
3. **Database Interactions**: All database interactions use object-oriented **PDO Prepared Statements**. This securely passes the sanitized data into the MySQL database, preventing SQL Injection attacks.
4. **State Management**: Once a user logs in, their data (User ID, Name, Role) is serialized and stored in **PHP Sessions (`$_SESSION`)**. This session data is evaluated on every page load to determine rendering logic and validation.

### Roles & Responsibilities
The system enforces security using custom `restrict` functions mapped to the session's active role.
* **Admin (`role = 'admin'`)**: 
  * Has global access. 
  * Can register new employees, modify employee profiles, and delete inactive employees.
  * Assesses and marks daily attendance for all active employees.
  * Dashboard provides aggregated statistics (total active employees, daily present/absent metrics).
* **Employee (`role = 'employee'`)**:
  * Has restricted access (prevented from modifying the system).
  * Can view their personal profile information.
  * Dashboard presents their individual attendance status for the current day.

---

## Project File Structure

```text
microproject/
│
├── assets/
│   └── css/               # Contains custom stylesheets (e.g., style.css)
│
├── config/
│   └── database.php       # PDO connection logic and DB credentials
│
├── includes/
│   ├── auth.php           # Security & Session management (isLoggedIn, isAdmin)
│   ├── header.php         # Shared UI markup, Bootstrap CSS links, Navigation bar
│   └── footer.php         # Shared UI closing markup, Bootstrap JS scripts
│
├── modules/               # Core Application Logic Component Files
│   ├── attendance/
│   │   ├── list.php       # View historical attendance records
│   │   └── mark.php       # Form to submit attendance data for employees
│   │
│   ├── auth/
│   │   ├── login.php      # Authenticates user and initiates $_SESSION
│   │   └── logout.php     # Destroys session and redirects
│   │
│   └── employee/
│       ├── add.php        # Form to add a new employee
│       ├── delete.php     # Script to delete an employee
│       ├── edit.php       # Form and script to modify employee data
│       ├── list.php       # Table displaying all network employees
│       └── profile.php    # Read-only specific employee view
│
├── public/
│   └── dashboard.php      # Main view rendered after successful login 
│
├── index.php              # Entrypoint router (redirects to dashboard)
├── database.sql           # Schema definition and default Admin insertion
└── README.md              # Project documentation
```

---

## Setup Instructions

1. **Environment Setup**: Ensure you have a local AMP stack running (like XAMPP, WAMP, or LAMP).
2. **Move to Server Directory**: Place the `microproject` directory in your web server's root (e.g., `C:/xampp/htdocs/` or `/opt/lampp/htdocs/`).
3. **Database Configuration**:
   * Open `phpMyAdmin` (typically http://localhost/phpmyadmin).
   * Note: You do not need to create a database manually. 
   * Go to the **Import** tab and upload the `database.sql` file provided in the project root. This will automatically generate the `ems` database, its tables, and the default admin user.
4. **Log In**:
   * Navigate to `http://localhost/microproject` in your browser.
   * **Default Admin Credentials:**
     * **Email**: admin@example.com
     * **Password**: admin123
