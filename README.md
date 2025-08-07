# Coupons Package

A Laravel package for managing discount coupons in the admin panel. This package provides CRUD operations, permission checks, and robust error handling for coupon management. The file structure and UI conventions follow the certificates package.

## Features
- Create, edit, delete coupons
- Set coupon type, amount, usage limits
- Restrict coupons to products or categories
- Track coupon usage per user
- Admin panel CRUD with authentication and permissions

## Installation
1. Add to your Laravel project (as a local package or via composer).
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Register the service provider if not auto-discovered.

## Usage
- Access coupon management via `/admin/coupons` (admin login required)
- Use resource routes for CRUD operations
- Apply coupons during checkout by validating code and rules

## Configuration
- See `config/coupons.php` for default settings

## License
MIT
