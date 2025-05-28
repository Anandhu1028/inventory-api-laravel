# Inventory Management & Dynamic Pricing API (Laravel)

This is a Laravel-based REST API project for managing products, warehouses, stock levels, and dynamic pricing logic.

## 🔐 Authentication

- **Login**: `POST /api/login`
  - Required: `email`, `password`
  - Returns Bearer Token for authenticated requests.

## 🛒 Product Endpoints

- `GET /api/products`: List all products with stock and dynamic pricing.
- `GET /api/products/pricing?product_id=1`: Calculate dynamic price for a product.
- `GET /api/products/{id}/price`: Fetch final price of a product.

## 📦 Stock

- `POST /api/stock`: Add new stock to a warehouse.

## 🏢 Warehouse

- `GET /api/warehouses/{id}/report`: Stock summary per warehouse.

## 🔧 Technologies Used

- Laravel 10+
- Sanctum for API Authentication
- MySQL for DB
- Laravel Jobs & Queues (optional bonus)
- Postman for testing

## 🧪 Postman Collection

Use the provided Postman collection:  
👉 `Inventory-API.postman_collection.json`

## ⚙️ Setup Instructions

```bash
git clone <repo-url>
cd project-folder
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
