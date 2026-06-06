# ADAM Fashion Website — Xeon BD Hosting Setup Guide

## Files in this package:
```
adam-xeonbd/
├── index.html          ← Main store (customers see this)
├── .htaccess           ← URL routing config (required!)
├── admin/
│   └── index.php       ← Admin panel
├── api/
│   ├── config.php      ← ⭐ EDIT THIS FIRST
│   ├── index.php       ← API router
│   ├── install.php     ← Run once to setup DB
│   └── endpoints/      ← API logic
└── uploads/
    └── products/       ← Product images stored here
```

---

## STEP-BY-STEP SETUP ON XEON BD

### Step 1 — Create MySQL Database
1. Login to your **Xeon BD cPanel**
2. Go to **MySQL Databases**
3. Create a new database (e.g. `adam_shop`)
4. Create a database user with a strong password
5. Add the user to the database with **ALL PRIVILEGES**
6. Note down: database name, username, password

### Step 2 — Edit config.php
Open `api/config.php` and fill in your details:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'adam_shop');      // your database name
define('DB_USER', 'adam_user');      // your username
define('DB_PASS', 'your_password');  // your password
```

### Step 3 — Upload All Files
1. Login to **cPanel → File Manager**
2. Open `public_html` folder
3. Upload the ENTIRE `adam-xeonbd` folder contents into `public_html`
4. Make sure file structure looks like:
   - `public_html/index.html`
   - `public_html/.htaccess`
   - `public_html/admin/index.php`
   - `public_html/api/config.php`
   - etc.

### Step 4 — Set Folder Permissions
In File Manager, right-click the `uploads/products` folder:
- Set permissions to **755** (or 777 if uploads don't work)

### Step 5 — Run the Installer
Visit in your browser:
```
https://yourdomain.com/api/install.php
```
You should see a green success page.

### Step 6 — DELETE install.php (IMPORTANT!)
After the installer runs successfully:
- Go to File Manager
- Delete `api/install.php`
- This is a security requirement!

### Step 7 — Done! Test Your Site
- **Store:** `https://yourdomain.com`
- **Admin:** `https://yourdomain.com/admin/`
- **Login:** username `admin`, password `adam2026`

---

## ADMIN PANEL FEATURES
- 📊 Dashboard with live sales charts
- 📦 Orders — view, update status, WhatsApp customers
- 👕 Products — add/edit/delete with REAL image upload
- 🏷️ Coupons — create & manage discount codes
- 👥 Customers — view all customers, WhatsApp them
- ⭐ Reviews — approve or delete customer reviews
- ⚙️ Settings — change store info & admin password

## CHANGING YOUR WHATSAPP NUMBER
1. Go to Admin → Settings
2. Update the WhatsApp number
3. Click Save

## DEFAULT COUPON CODES
- `ADAM2FREE` — Free delivery on 2+ items
- `ADAM10`    — 10% off any order

## SUPPORT
WhatsApp: 01675760715
