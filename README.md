# Inventory Management & Dynamic Pricing API

This is a Laravel-based RESTful API for managing inventory, warehouses, and dynamic product pricing based on stock levels and expiry.

## 🔧 Features

* ✅ User Authentication (Sanctum)
* 📦 Product Management
* 🏬 Warehouse Management
* 📊 Stock Tracking
* 💰 Dynamic Pricing based on stock and expiry
* 📈 Reporting Endpoints
* 🧪 Postman API Testing

---

## 🛠 Installation Guide

1. **Clone the repository**

```bash
git clone https://github.com/Anandhu1028/inventory-api-laravel.git
cd inventory-api-laravel
```

2. **Install dependencies**

```bash
composer install
npm install && npm run dev
```

3. **Setup `.env` file**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** in `.env`

```
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations and seeders (if any)**

```bash
php artisan migrate
```

6. **Serve the application**

```bash
php artisan serve
```

---

## 🔐 Authentication

* Uses **Laravel Sanctum** for API authentication
* Register/Login to receive token

### Register

POST `/api/register`

### Login

POST `/api/login`

> Use `Authorization: Bearer {token}` in headers for all protected routes.

---

## 🚀 API Endpoints

### Products

* `GET /api/products` — List products with dynamic pricing
* `GET /api/products/pricing?product_id=1` — Dynamic pricing details
* `GET /api/products/{id}/price` — Final dynamic price

### Warehouses

* `GET /api/warehouses` — List warehouses
* `POST /api/warehouses` — Add new warehouse

### Stock

* `POST /api/stock` — Add or update stock

### Reporting

* (Add report endpoint here if implemented)

---

## 📫 Postman Collection

> ## Postman Collection

You can test all API endpoints using the provided Postman collection.

📁 [Download / View Collection](postman/inventory-api-postman-collection.json)

To use:
1. Open Postman.
2. Go to `File > Import`.
3. Choose the file `inventory-api-postman-collection.json` from the `postman/` directory.
4. Use the requests to test all functionality: Products, Warehouses, Stock, Pricing, Auth, etc.


---

## 🧑‍💻 Author

**Anandhu A S**

* [GitHub Profile](https://github.com/Anandhu1028)

---

## 📃 License

This project is open-source and available under the [MIT License](LICENSE).
