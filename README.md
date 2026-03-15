#Flood Relief Management System

A web-based system to manage flood relief requests in Sri Lanka. Affected persons can submit and track relief requests, while admins can manage users, review requests, and generate reports.

## Features

**Affected Person (User)**
- Register & login
- Submit relief requests (Food, Water, Medicine, Shelter)
- View, edit, and delete submitted requests
- Track request status

**Admin**
- Dashboard with statistics (total users, high severity cases, total requests)
- View and manage registered users
- View detailed user profiles and their requests
- Generate filtered reports by district and relief type

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP |
| Database | MySQL |
| Server | XAMPP / WAMP |

## Project Structure

```
├── index.html                  # User login (entry point)
├── register.html               # User registration
├── admin_login.html            # Admin login
├── admin_register.html         # Admin registration
├── user_dashboard.html         # User home page
├── create_request.html         # Submit relief request
├── my_requests.html            # View/manage requests
├── edit_request.html           # Edit a request
├── admin_dashboard.html        # Admin dashboard with stats
├── users.html                  # Admin: manage users
├── user_details.html           # Admin: view user details
├── reports.html                # Admin: filtered reports
├── css/
│   ├── style.css               # Base styles & components
│   └── pages.css               # Page-specific styles
├── js/
│   ├── common.js               # Shared utilities
│   ├── auth.js                 # Login & register logic
│   ├── user.js                 # User-side functionality
│   └── admin.js                # Admin-side functionality
├── api/                        # PHP backend (18 endpoints)
│   ├── db_connect.php          # Database connection
│   ├── login.php               # User login
│   ├── register.php            # User registration
│   ├── admin_login.php         # Admin login
│   ├── admin_register.php      # Admin registration
│   ├── submit_request.php      # Create relief request
│   ├── get_my_requests.php     # Get user's requests
│   ├── get_request.php         # Get single request
│   ├── update_request.php      # Update a request
│   ├── delete_request.php      # Delete a request
│   ├── get_all_users.php       # Get all users (admin)
│   ├── get_user_details.php    # Get user details (admin)
│   ├── delete_user.php         # Delete a user (admin)
│   ├── get_all_requests.php    # Get all requests (admin)
│   ├── get_stats.php           # Dashboard stats (admin)
│   ├── get_reports.php         # Filtered reports (admin)
│   ├── get_profile.php         # Get logged-in user profile
│   └── logout.php              # Logout
└── db/
    └── flood_relief_db.sql     # Database schema & seed data
```

## Setup Instructions

1. **Install XAMPP** 

2. **Copy project** — Place this folder inside C:\xampp\htdocs\

3. **Start services** — Open XAMPP Control Panel and start **Apache** and **MySQL**

4. **Create database** — Open [phpMyAdmin](http://localhost/phpmyadmin), go to the **Import** tab, and import db/flood_relief_db.sql
			 If any error occurred please try again by disable foreign key checks

5. **Open the app** — Visit `http://localhost/your-folder-name/index.html or folder name

## Default Admin Account

| Email | Password |
|-------|----------|
| admin@test.com | admin123 |

(By Grooup-12)
