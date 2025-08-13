# Admin Coupon Manager
This package provides an Admin Coupon Manager for managing discount coupons within your application.
---

## Features
- Create, edit, delete coupons
- Assign coupons to categories, products, or courses
- Support for soft deletes in pivot tables (coupon–category, coupon–product, coupon–course)
- Define coupon codes, discounts, usage limits, and validity periods
- Easily integrate with checkout and order systems
- Support for module publishing into Modules/Coupons for customization
---

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

---

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-coupon-discounts.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/coupons:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan coupons:publish --force
    ```
---

## Usage
1. Create: Add a new coupon with details like code, discount type (percentage/fixed), value, start date, and expiry date.
2. Assign: Link coupons to categories, products, or courses using pivot tables.
3. Update: Edit coupon details or change applicable items.
4. Delete: Soft delete coupons or pivot records to preserve historical order data.

---

### Admin Panel Routes
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET    | `/coupons` | List all coupons |
| GET    | `/coupons/create` | Create coupon form |
| POST   | `/coupons` | Store new coupon |
| GET    | `/coupons/{id}` | Show coupon details |
| GET    | `/coupons/{id}/edit` | Edit coupon form |
| PUT    | `/coupons/{id}` | Update coupon |
| DELETE | `/coupons/{id}` | Delete coupon |
| POST | `/coupons/updateStatus` | Update coupon status|

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin coupon routes here
});
```
---

## Database Tables

- `coupons` - Stores coupon details.
- `coupon_category` - Pivot table linking coupons to category.  
- `coupon_course` - Pivot table linking coupons to courses.
- `coupon_product` - Pivot table linking coupons to products.

---

## License

This package is open-sourced software licensed under the MIT license.
