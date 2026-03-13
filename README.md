# 🔐 Secure Authentication Dashboard with 2FA

A secure web-based authentication system built with **PHP CodeIgniter 3**, featuring 
Two-Factor Authentication (TOTP), JWT session management, and device fingerprinting.

---

## ✨ Features

- ✅ Secure login with **bcrypt** password hashing
- ✅ **TOTP Two-Factor Authentication** (works with Google Authenticator / Authy)
- ✅ **JWT** session management
- ✅ **Device fingerprinting** using HMAC-SHA256
- ✅ Security logging and activity tracking
- ✅ CodeIgniter 3 MVC architecture

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP / CodeIgniter 3 |
| Database | MySQL |
| Auth | bcrypt + JWT |
| 2FA | TOTP (RFC 6238) |
| Security | HMAC-SHA256 device fingerprinting |

---

## ⚙️ Setup Instructions

### 1. Clone the repository
```bash
git clone https://github.com/Mohamed-Yasar2026/secure-auth-dashboard.git
cd secure-auth-dashboard
```

### 2. Install dependencies
```bash
composer install
```

### 3. Configure the database
```bash
cp application/config/database.example.php application/config/database.php
```
Edit `database.php` and fill in your MySQL credentials.

### 4. Configure the app
```bash
cp application/config/config.example.php application/config/config.php
```
Edit `config.php`:
- Set your `base_url`
- Set a strong `encryption_key`

### 5. Set JWT secret
In `index.php`, set your JWT secret via environment variable:
```bash
JWT_SECRET=your_strong_secret_here
```

### 6. Import database
Import the SQL file into your MySQL database.

### 7. Run
Visit `http://localhost/authentication/` in your browser.