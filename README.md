# iso_mem  
**Membership, Events & Voting Management System for ISOC (MEV)**  

A lightweight PHP application that lets administrators manage members, events, candidates, and voting positions, while providing a simple interface for users to register, view events, and cast votes.

---  

## Overview  

`iso_mem` is a web‑based system designed for the **Internet Society (ISOC) – MEV** chapter. It offers:

* Secure admin panel for CRUD operations on users, events, candidates, and voting positions.  
* Public pages for registration, login, event details, and voting.  
* Attendance tracking and winner announcement features.  

All core functionality is implemented in pure PHP with a MySQL backend, making it easy to deploy on any standard LAMP stack.

---  

## Features  

| Category | Feature |
|----------|---------|
| **Administration** | • Admin authentication (`admin_login.php`)  <br>• Dashboard (`admin_home.php`)  <br>• Manage users, events, candidates, and voting positions  <br>• Edit pages for candidates, events, positions  <br>• View attendance, candidates, events, voting positions  <br>• Upload and display images (e.g., school logos) |
| **User Experience** | • User registration (`register.php`)  <br>• Login / logout (`login.php`, `logout.php`)  <br>• Event list (`candidate_list.php`)  <br>• Detailed event view (`event_details.php`)  <br>• Voting interface (integrated in event details) |
| **Reporting** | • Winner calculation (`winner.php`)  <br>• Attendance reports (`view_attendance.php`) |
| **Utilities** | • Central navigation bar (`navbar.php`)  <br>• Reusable configuration (`config.php`)  <br>• Database schema (`Database/isomev.sql`) |

---  

## Tech Stack  

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL / MariaDB |
| **Web Server** | Apache (or any server supporting PHP) |
| **Frontend** | HTML5, CSS3 (Bootstrap optional), minimal JavaScript |
| **Documentation** | Microsoft Word (`Membership, Events & Voting Management system for ISOC(MEV).docx`) |

---  

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/yourusername/iso_mem.git
   cd iso_mem
   ```

2. **Create a MySQL database**  

   ```sql
   CREATE DATABASE iso_mem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Import the schema**  

   ```bash
   mysql -u YOUR_DB_USER -p iso_mem < Database/isomev.sql
   ```

4. **Configure the application**  

   - Copy `config.sample.php` (or edit the existing `config.php`) and set your database credentials:  

     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'iso_mem');
     define('DB_USER', 'YOUR_DB_USER');
     define('DB_PASS', 'YOUR_DB_PASSWORD');
     ```

   - Adjust any other constants (e.g., site URL) as needed.

5. **Set up the web server**  

   - Place the project folder in your web root (e.g., `/var/www/html/iso_mem`).  
   - Ensure the `admin/uploads/` directory is writable by the web server for image uploads.  

6. **Secure the admin area** (optional but recommended)  

   - Move `admin` outside the public document root or protect it with `.htaccess` rules.  

7.