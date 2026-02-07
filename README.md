Here you go — clean, **interview-ready `README.md`** formatting.
You can **copy-paste this directly** into a `README.md` file 👌

---

```md
# 📦 Prominno Labs – Laravel API Project

This repository contains a **role-based REST API** built using **Laravel**, designed to manage **Admin & Seller workflows**, including **product management with multiple brands**, **image uploads**, **PDF generation**, and **secure authentication**.

---

## 🛠 Tech Stack

- **Backend:** Laravel 11  
- **Authentication:** Laravel Sanctum (Token-based)  
- **Database:** MySQL / MariaDB  
- **PDF Generation:** barryvdh/laravel-dompdf  
- **Storage:** Local filesystem (public disk)  
- **Testing Tools:** PowerShell / Postman / curl  

---

## 🔐 Authentication Overview

- Token-based authentication using **Laravel Sanctum**
- Two roles:
  - **Admin**
  - **Seller**

All protected routes require:

```

Authorization: Bearer <ACCESS_TOKEN>

```

---

## 🧑‍💼 ADMIN SIDE APIs

### 1️⃣ Admin Login API

**Endpoint**
```

POST /api/admin/login

````

**Request Body (JSON)**
```json
{
  "email": "info@prominno.com",
  "password": "password"
}
````

**Response**

```json
{
  "status": true,
  "message": "Admin login successful",
  "token": "access_token_here",
  "role": "admin"
}
```

---

### 2️⃣ Create Seller API

**Endpoint**

```
POST /api/admin/sellers
```

**Headers**

```
Authorization: Bearer ADMIN_TOKEN
```

**Request Body (JSON)**

```json
{
  "name": "John Seller",
  "email": "seller@test.com",
  "mobile": "9876543210",
  "country": "India",
  "state": "Delhi",
  "password": "password",
  "skills": [1, 2]
}
```

**Features**

* ✔ Multiple skills supported
* ✔ Password hashing
* ✔ Validation & exception handling

---

### 3️⃣ Seller Listing API (Pagination)

**Endpoint**

```
GET /api/admin/sellers?page=1
```

**Headers**

```
Authorization: Bearer ADMIN_TOKEN
```

**Response**

* Paginated seller list
* Includes skill relationships

---

## 🧑‍💼 SELLER SIDE APIs

### 4️⃣ Seller Login API

**Endpoint**

```
POST /api/seller/login
```

**Request Body (JSON)**

```json
{
  "email": "seller@test.com",
  "password": "password"
}
```

**Response**

```json
{
  "status": true,
  "token": "seller_access_token",
  "role": "seller"
}
```

---

### 5️⃣ Add Product API (Multiple Brands + Images)

**Endpoint**

```
POST /api/seller/products
```

**Headers**

```
Authorization: Bearer SELLER_TOKEN
Content-Type: multipart/form-data
```

**Request Fields**

```
name=Mouse
description=Test
brands[0][name]=Dell
brands[0][detail]=Test
brands[0][price]=1000
brands[0][image]=<file>
brands[1][name]=HP
brands[1][detail]=Test
brands[1][price]=2000
brands[1][image]=<file>
```

**Features**

* ✔ Multiple brands per product
* ✔ Image upload
* ✔ Transaction-safe creation
* ✔ Ownership enforced

---

### 6️⃣ Product Listing API (Seller-only)

**Endpoint**

```
GET /api/seller/products?page=1
```

**Headers**

```
Authorization: Bearer SELLER_TOKEN
```

**Features**

* ✔ Only authenticated seller products
* ✔ Pagination
* ✔ Includes brand details

---

### 7️⃣ Product PDF View API

**Endpoint**

```
GET /api/seller/products/{id}/pdf
```

**Headers**

```
Authorization: Bearer SELLER_TOKEN
```

**PDF Includes**

* Product Name
* Product Description
* Brand Name
* Brand Image
* Brand Price
* Total Price (sum of brand prices)

---

### 8️⃣ Delete Product API

**Endpoint**

```
DELETE /api/seller/products/{id}
```

**Headers**

```
Authorization: Bearer SELLER_TOKEN
```

**Features**

* ✔ Seller can delete only own product
* ✔ Brand records auto-deleted
* ✔ Brand images removed from storage
* ✔ Proper error handling

---

## 📁 Folder Structure (Important)

```
app/
 └── Http/
     └── Controllers/
         └── Api/
             ├── Admin/
             │   ├── AuthController.php
             │   └── SellerController.php
             └── Seller/
                 └── ProductController.php

app/
 └── Models/
     ├── User.php
     ├── Product.php
     ├── Brand.php
     └── Skill.php

resources/
 └── views/
     └── pdf/
         └── product.blade.php
```

---

