# Zoom EV 🚗🔧

**Automotive Garage Management & Digital Vehicle Passport Infrastructure**  
*Built with Laravel 12, Filament v3, Livewire, SQLite / MySQL, and Spatie Permission.*

---

## 📌 Executive Overview

**Zoom EV** is an enterprise-grade digital workshop management platform engineered specifically for automotive service centers, fleet managers, and vehicle owners in Ethiopia. 

The platform bridges physical workshop operations with permanent cloud-backed vehicle history records. It provides paperless multi-stage vehicle intake, digital voice notes, automated inventory stock deductions, multi-branch invoicing, and public tamper-evident **Digital Vehicle Passports** resolved via thermal QR code stickers.

---

## 🌟 Key Features & Architecture

### 1. 🛡️ Digital Vehicle Passport & Tamper-Evident History
* **Permanent QR Code Identity:** Every registered vehicle is assigned an unguessable UUID-based QR code sticker (sized 60mm × 40mm for windshield / B-pillar application).
* **Public Passport Verification (`/passport/{uuid}`):** Prospective car buyers or vehicle owners can scan the QR code to verify service records, certified check-in odometer logs, replaced parts, and inspection health scores without logging in.
* **Tamper-Evident Odometer Tracking:** Logs mileage at every workshop intake and flags anomalies against historical visits.
* **Algorithmic Vehicle Health Score:** Computes category ratings (Engine, Brakes, Transmission, Electrical, Suspension) from digitized multi-point inspection checklists.

### 2. 📋 Workshop Operations & Digital Job Cards
* **Multi-Stage Photo Intake:** Captures and archives check-in photos, damage photos, before-service photos, after-service photos, and final checkout condition.
* **Audio Voice Notes:** Workshop staff and service advisors can record audio voice notes directly into job cards using the browser audio capture widget.
* **Role-Based Workflow:** Work orders move through structured milestones:  
  `pending` (Intake) → `in_progress` (Under Repair) → `quality_check` (Supervisor Review) → `completed` (Ready for Billing) → `cancelled`.
* **Automated Stock Deductions:** Adding parts to a job card immediately deducts `quantity_on_hand` in inventory and logs an immutable audit trail in `stock_movements`.

### 3. 📝 Diagnostic Quotations & Defect Price Estimation
* **Customer Questionnaire (`/request-a-quote`):** Vehicle owners specify their car details (or choose from registered vehicles), select diagnostic categories, check off observed symptoms (warning lights, noises, leaks, vibrations), specify drivability condition and urgency level.
* **Photo Damage Documentation:** Customers can upload multiple high-resolution photos of defect areas, dashboard warning lights, or collision damage with instant browser previews.
* **Workshop Admin Assessment (`/admin/quotations`):** Workshop managers and service advisors review questionnaire details and inspect uploaded defect photos in a gallery.
* **Official Estimation Builder:** Formulate the diagnostic assessment ("what works will be done") and itemize estimated labor services and replacement spare parts with ETB pricing.
* **Automated Multi-Channel Notifications:**
  - Staff are notified instantly via Dashboard Bell Icon and email on new inquiries.
  - Customers receive an immediate confirmation with reference number `#QUO-XXXXX`.
  - Admin dispatches the official formal Quotation Report to the customer's email with 1 click.
* **1-Click Conversion:** Approved quotations can be converted directly into active Workshop Job Cards or Appointments.

### 4. 👥 Staff Work Delegation & Multi-Channel Notifications
* **Technician Task Delegation:** When a job card is assigned or reassigned to a mechanic, the system dispatches:
  1. A queued email with complete work details (plate, model, customer symptoms, priority, direct link).
  2. An instant notification badge in the workshop dashboard bell icon (`notifications` table).
  3. Real-time updates to Admin oversight.
* **Front-Desk Alerts:** Receptionists, service advisors, and managers receive instant notifications whenever a new customer registers or requests an appointment.
* **Cashier Settlements:** Cashiers and managers are automatically notified the moment a job card is marked `completed` and the final invoice is ready for payment.

### 5. 💻 Customer Portal (Adminator UI)
* **Modern Dual-Theme Portal:** Beautiful light and dark mode themes tailored for Ethiopian vehicle owners.
* **Fleet Dashboard:** View all registered vehicles, maintenance schedules, loyalty points, and upcoming bookings in one place.
* **Self-Service Appointments (`/book-a-service` & `/portal/appointments`):** Schedule service visits with automatic slot checks and staff notifications.
* **Digital History & Invoices:** Search and download past service records and itemized invoices.

### 6. 🔐 Security & Role-Based Access Control (RBAC)
* **7 Tailored Staff Roles:** `admin`, `manager`, `service_advisor`, `mechanic`, `receptionist`, `cashier`, `inventory_manager`.
* **81 Granular Permissions:** Strictly governed by Laravel Gate policies and Spatie Laravel Permission.
* **Interactive Matrix Manager (`/admin/role-permissions`):** System administrators can toggle individual permissions or entire module groups with lockout safeguards.

---

## 🛠️ Server & System Requirements

Ensure your hosting server meets the following requirements:

| Component | Minimum Requirement | Recommended |
|---|---|---|
| **Operating System** | Ubuntu 22.04 LTS / Debian 12 / AlmaLinux 9 / Windows Server | Ubuntu 24.04 LTS |
| **PHP Version** | PHP 8.2.0 or higher | PHP 8.3 |
| **PHP Extensions** | `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_sqlite` / `pdo_mysql`, `tokenizer`, `xml`, `zip` | All enabled |
| **Database** | SQLite 3.35+ or MySQL 8.0+ / MariaDB 10.6+ | MySQL 8.0 / SQLite (Single Branch) |
| **Web Server** | Apache 2.4+ (with `mod_rewrite`) or Nginx 1.20+ | Nginx + PHP-FPM |
| **Process Manager** | Supervisor or Systemd (for Queue Workers) | Systemd / Supervisor |
| **Node.js** | Node 18.x + NPM (only for building frontend assets) | Node 20 LTS |

---

## 🚀 Production Hosting & Deployment Guide

Follow these step-by-step instructions to host **Zoom EV** on any VPS, cloud server, or shared hosting environment (e.g. cPanel, DigitalOcean, Hetzner, AWS):

### Step 1: Upload / Clone the Codebase
Extract your project files or clone the repository to your server's web root:
```bash
cd /var/www
git clone <your-repository-url> zoomev
cd /var/www/zoomev
```

### Step 2: Set File Permissions
Ensure the web server user (`www-data`, `nginx`, or `apache`) has read/write permissions to `storage` and `bootstrap/cache`:
```bash
sudo chown -R www-data:www-data /var/www/zoomev
sudo chmod -R 775 /var/www/zoomev/storage
sudo chmod -R 775 /var/www/zoomev/bootstrap/cache
```

### Step 3: Configure Environment Variables
Copy `.env.example` to `.env` and configure your production settings:
```bash
cp .env.example .env
nano .env
```
Key production variables to check:
```ini
APP_NAME="Zoom EV"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.et

# Database (e.g. MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zoom_autotrack
DB_USERNAME=autotrack_user
DB_PASSWORD=YourStrongDatabasePassword

# Queue Driver (database recommended for queued notifications)
QUEUE_CONNECTION=database

# SMTP Mail Server
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.et
MAIL_PORT=465
MAIL_USERNAME=notifications@yourdomain.et
MAIL_PASSWORD=YourMailPassword
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=notifications@yourdomain.et
MAIL_FROM_NAME="${APP_NAME}"
ADMIN_NOTIFICATION_EMAIL=admin@yourdomain.et
```

### Step 4: Install Dependencies
```bash
# Install PHP production dependencies
composer install --no-dev --optimize-autoloader

# Generate secure application encryption key
php artisan key:generate

# Create public storage symlink for uploaded vehicle photos and voice notes
php artisan storage:link
```

### Step 5: Database Migrations & Initial Data
Run the database migrations to build all 33 tables:
```bash
php artisan migrate --force
```
*(Optional: If setting up a fresh demonstration environment with default test roles and branches)*:
```bash
php artisan db:seed --force
```

### Step 6: Production Caching & Speed Optimization
Run these commands on your production server to compile routes, views, and configurations for peak performance:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan icons:cache
```
*(Note: Whenever you modify `.env` or configuration files, run `php artisan optimize:clear` followed by `php artisan optimize`)*.

---

## 🌐 Web Server Virtual Host Configuration

### Nginx Virtual Host (Recommended)
Create `/etc/nginx/sites-available/zoomev.conf`:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.et www.your-domain.et;
    root /var/www/zoomev/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    # Allow audio voice recordings and high-resolution vehicle photo uploads
    client_max_body_size 32M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache `.htaccess` / Virtual Host
Ensure `DocumentRoot` points directly to the `/public` directory:
```apache
<VirtualHost *:80>
    ServerName your-domain.et
    DocumentRoot /var/www/zoomev/public

    <Directory /var/www/zoomev/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/zoomev_error.log
    CustomLog ${APACHE_LOG_DIR}/zoomev_access.log combined
</VirtualHost>
```

---

## ⚙️ Background Services & Automation

### 1. Background Queue Worker (Supervisor)
To process queued emails and notifications in the background without slowing down user interactions, configure **Supervisor**:

Create `/etc/supervisor/conf.d/zoomev-worker.conf`:
```ini
[program:zoomev-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/zoomev/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/zoomev/storage/logs/worker.log
stopwaitsecs=3600
```
Then start the worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start zoomev-worker:*
```

### 2. Scheduled Cron Job (Maintenance Reminders)
Add the Laravel schedule runner to the server crontab:
```bash
crontab -e -u www-data
```
Add the following line:
```cron
* * * * * cd /var/www/zoomev && php artisan schedule:run >> /dev/null 2>&1
```
This automatically runs maintenance mileage checks and overdue vehicle alerts.

---

## 🔑 Default Accounts & Access Directory

| Portal | Login URL | Role / Persona | Email | Password |
|---|---|---|---|---|
| **Workshop Admin** | `/admin/login` | System Administrator | `admin@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Lead Administrator | `zola2311@gmail.com` | *(Account Pass)* |
| **Workshop Admin** | `/admin/login` | Workshop Manager | `manager@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Service Advisor | `advisor@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Lead Mechanic | `mechanic1@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Service Mechanic | `mechanic2@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Receptionist | `reception@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Cashier / Billing | `cashier@autotrack.et` | `password` |
| **Workshop Admin** | `/admin/login` | Inventory Manager | `inventory@autotrack.et` | `password` |
| **Customer Portal** | `/portal/login` | Demo Customer (5 Vehicles) | `client@example.com` | `password` |
| **Customer Portal** | `/portal/login` | Commercial Fleet Customer | `fleet@example.com` | `password` |

*(Tip: On the `/admin/login` page, you can click any of the 1-click test role buttons to instantly autofill credentials for testing).*

---

## 🛠️ Common Maintenance Commands

* **Clear All Caches (Development):**
  ```bash
  php artisan optimize:clear
  ```
* **Rebuild Production Caches (After Deploy):**
  ```bash
  php artisan optimize
  php artisan view:cache
  ```
* **Restart Background Workers:**
  ```bash
  php artisan queue:restart
  ```
* **Verify System Health & Routes:**
  ```bash
  php artisan route:list
  ```

---

## 📄 License & Intellectual Property

Copyright &copy; 2026 **Zoom EV**. All rights reserved.  
Unauthorized copying, modification, distribution, or commercial deployment without prior written authorization is strictly prohibited.
