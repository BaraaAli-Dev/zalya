# Zalya — Perfume E-Commerce Platform

A full-stack e-commerce web application built with **Laravel 12**, **Inertia.js**, and **Vue 3**, for a fragrance/perfume brand. This project includes a complete customer-facing storefront and a separate admin dashboard for managing products, categories, and orders.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | Vue 3 + Inertia.js |
| Styling | Tailwind CSS |
| Database | MySQL |
| Auth | Laravel Breeze (customer) + custom guard (admin) |

---

## Project Structure & Key Concepts

### 1. Dual Authentication System
The project uses **two separate Laravel auth guards**:
- `web` — for regular customers (`/login`, `/register`)
- `admin` — for the admin dashboard (`/admin/login`)

These guards use **independent sessions**, meaning a customer and an admin can be logged in simultaneously from different browsers/devices without interfering with each other. The `role` column on the `users` table (`admin` / `customer`) is checked on every admin login attempt as an extra safeguard.

Admin routes are fully separated under `routes/web.php`, grouped under `prefix('admin')->name('admin.')`, and protected by the `auth:admin` + custom `IsAdmin` middleware.

### 2. Product Variants (Sizes)
Products support **multiple sizes**, each with its own price and stock, via a `product_variants` table:
```
products (name, description, images, category, gender)
    └── product_variants (size, price, stock)
```
- If a product has **only one size**, the storefront skips the size-selection step and allows adding to cart directly.
- If a product has **multiple sizes**, the customer must select a size on the product page before adding to cart.

### 3. Stock Management (Important — Read This)
Stock is **only decremented at checkout confirmation**, not when an item is added to the cart. This prevents "stock leaks" where abandoned carts would permanently reduce available inventory. The checkout process uses a database transaction with row locking (`lockForUpdate`) to safely handle concurrent purchases of the same item.

### 4. Cart System
The cart is **session-based** (not database-based), which means:
- Guests can add items to the cart without an account.
- The cart is tied to the browser session and is cleared after checkout or when the session expires.

### 5. Guest Checkout
Customers can complete a purchase **without creating an account**. Shipping and contact details are stored directly on the `orders` table (not linked to a saved `addresses` record), so historical orders remain accurate even if a logged-in customer later changes their profile info.

---

## What's Included

- ✅ Admin dashboard (stats, low stock alerts, recent orders)
- ✅ Product management with multiple sizes/variants and multi-image upload (with drag-to-reorder & "set as main image")
- ✅ Category management
- ✅ Order management (view, update status, delete)
- ✅ Storefront homepage with category/gender filtering
- ✅ Live product search
- ✅ Product detail pages with size selection
- ✅ Slide-out cart drawer
- ✅ Guest & authenticated checkout
- ✅ Customer account area (dashboard, order history, profile)
- ✅ Order confirmation emails (Markdown mail template)

## What's NOT Included (You'll Need to Add These)

- ❌ **Real payment gateway** — only "Cash on Delivery" is implemented. You'll need to integrate a provider (e.g. Paymob, Fawry, Stripe) before accepting real payments.
- ❌ **Production email service** — `.env` is currently set to `MAIL_MAILER=log` for local testing. You must configure a real SMTP provider (Mailtrap for staging, SendGrid/Postmark for production) before going live.
- ❌ **Automated tests** — no Feature/Unit test suite has been written yet.
- ❌ **Saved address book** — the `addresses` table exists but has no UI built for it; customers currently re-enter shipping info at every checkout.
- ❌ **Load testing** — the app has not been tested under real production traffic.

---

## Local Setup

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then:
php artisan migrate --seed
php artisan storage:link

# Run the app
php artisan serve
npm run dev
```

### Default Seeded Admin Account
After seeding, manually update a user's `role` column to `admin` in the database to access `/admin/login`, or create one via `php artisan tinker`.

---

## Before Deploying to Production

1. Set `APP_DEBUG=false` and `APP_ENV=production` in `.env`.
2. Configure a real `MAIL_MAILER` (see above).
3. Integrate a real payment gateway if accepting online payments.
4. Never commit your `.env` file — ensure it's in `.gitignore`.
5. Review and adjust `SESSION_DRIVER` (currently supports both `file` and `database`; run `php artisan migrate` for the `sessions` table if using `database`).
6. Set up a queue worker (`php artisan queue:work`) if you switch mail sending to `ShouldQueue` for better performance under load.
7. Run `npm run build` (not `npm run dev`) for production assets.

---

## Brand Colors (Tailwind config)

Defined in `tailwind.config.js` under the `brand` color palette (`brand-50` through `brand-900`), based on the Zalya brand identity (`#3A4E3F` deep green, `#F5C99C` warm beige).

---

## License / Ownership

This codebase is provided as a **starter structure**, not a finished, ready-to-sell product. Please review the "What's NOT Included" section above carefully before launching a live store with real customers and payments.
